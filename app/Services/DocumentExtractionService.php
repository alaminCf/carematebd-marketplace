<?php

namespace App\Services;

use App\Models\Caregiver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DocumentExtractionService
{
    /**
     * Process an uploaded document for OCR and structured data extraction.
     * Supports:
     * - 'nid_front': Extracts name, name_bn, nid_number, date_of_birth
     * - 'nid_back': Extracts blood_group, address/pin, issuance
     * - 'academic': Extracts degree/qualification, institution, passing_year
     * - 'certificate': Extracts cert_name, institution, cert_number, issue_year
     */
    public function extract(UploadedFile $file, string $docType, ?string $clientText = null): array
    {
        $mime = $file->getClientMimeType() ?: 'application/octet-stream';
        $size = $file->getSize() ?: 0;
        $originalName = $file->getClientOriginalName();

        // 1. Validate file constraints
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'application/pdf'];
        if (! in_array($mime, $allowedMimes, true) && ! in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'webp', 'pdf'], true)) {
            return [
                'status' => 'failed',
                'error' => 'Unsupported file format. Please upload JPG, PNG, WEBP, or PDF.',
                'extracted' => [],
                'confidence' => [],
            ];
        }

        if ($size > 10 * 1024 * 1024) {
            return [
                'status' => 'failed',
                'error' => 'File size exceeds maximum 10MB limit.',
                'extracted' => [],
                'confidence' => [],
            ];
        }

        // If client-side OCR recognized text directly from browser
        if (! empty($clientText)) {
            $clientParsed = $this->parseExtractedText($clientText, $docType);
            if (! empty($clientParsed['extracted'])) {
                return $clientParsed;
            }
        }

        // 2. Perform OCR / text extraction based on configured driver
        $rawText = '';
        $driver = config('services.ocr.driver', env('OCR_DRIVER', 'auto'));

        try {
            // Priority 1: Gemini AI / Vision if GEMINI_API_KEY is configured
            if (($driver === 'gemini' || $driver === 'auto') && env('GEMINI_API_KEY')) {
                $aiResult = $this->extractWithGeminiVision($file, $docType);
                if ($aiResult && ($aiResult['status'] ?? '') === 'success') {
                    return $aiResult;
                }
            }

            // Priority 2: Google Cloud Vision API if GOOGLE_VISION_KEY is configured
            if (($driver === 'google_vision' || $driver === 'auto') && env('GOOGLE_VISION_KEY')) {
                $rawText = $this->extractWithGoogleVision($file);
            }

            // Priority 3: Local Tesseract OCR if binary is installed
            if (empty($rawText) && ($driver === 'tesseract' || $driver === 'auto') && $this->isTesseractAvailable()) {
                $rawText = $this->extractWithTesseract($file);
            }

            // Priority 4: PDF text extraction (if PDF)
            if (empty($rawText) && str_contains($mime, 'pdf')) {
                $rawText = $this->extractFromPdf($file);
            }

            // Priority 5: High-speed Cloud OCR (OCR.space - supports Bengali & English)
            if (empty($rawText) && ! str_contains($mime, 'pdf')) {
                $rawText = $this->extractWithOcrSpace($file, $docType);
            }
        } catch (\Throwable $e) {
            Log::warning('DocumentExtractionService exception during raw OCR: '.$e->getMessage());
        }

        // 3. Fallback / Regex-based extraction on parsed text
        if (! empty($rawText)) {
            return $this->parseExtractedText($rawText, $docType);
        }

        // Graceful fallback: return clear notice without blocking caregiver registration
        return [
            'status' => 'manual_review',
            'notes' => 'Document received and secured. Automatic text recognition could not read all details clearly. Please review and fill or verify the fields manually.',
            'extracted' => [],
            'confidence' => [],
        ];
    }

    /**
     * Gemini Vision extraction using native HTTP client.
     */
    protected function extractWithGeminiVision(UploadedFile $file, string $docType): ?array
    {
        $apiKey = env('GEMINI_API_KEY');
        if (! $apiKey) {
            return null;
        }

        $base64 = base64_encode(file_get_contents($file->getRealPath()));
        $mime = $file->getClientMimeType() ?: 'image/jpeg';

        $prompt = match ($docType) {
            'nid_front', 'nid' => 'You are an official Bangladeshi National ID (NID) document analyzer. Extract all visible information accurately in JSON format with keys:
            - "name": full name in English
            - "name_bn": full name in Bengali
            - "nid_number": 10, 13, or 17 digit NID number (digits only, no spaces)
            - "date_of_birth": YYYY-MM-DD
            - "confidence": object containing confidence scores between 0.0 and 1.0 for each field.
            Do not invent or assume missing fields.',
            'nid_back' => 'Analyze this Bangladeshi National ID (NID) back image. Extract JSON with keys:
            - "blood_group": blood group (e.g. A+, B+, O+, AB+)
            - "present_address": address if readable
            - "permanent_address": address if readable
            - "confidence": object with confidence scores (0.0 to 1.0).',
            'academic' => 'Analyze this academic educational certificate or marksheet. Extract JSON with keys:
            - "education_qualification": examination/degree (e.g. SSC, HSC, B.Sc in Nursing, Diploma)
            - "education_institution": school, college, or university name
            - "education_passing_year": passing year (4 digits)
            - "candidate_name": name on certificate
            - "confidence": object with confidence scores (0.0 to 1.0).',
            'certificate' => 'Analyze this professional caregiving / nursing / medical training certificate. Extract JSON with keys:
            - "cert_name": title of course or certificate
            - "cert_institution": issuing organization, academy, or council
            - "cert_number": registration or certificate serial number if present
            - "cert_year": year issued (4 digits)
            - "confidence": object with confidence scores (0.0 to 1.0).',
            default => 'Analyze this credential document and extract all identifying titles, dates, institutions and candidate names in JSON format.'
        };

        $response = Http::timeout(20)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt.' Respond ONLY with a valid JSON object.'],
                        [
                            'inline_data' => [
                                'mime_type' => $mime,
                                'data' => $base64,
                            ],
                        ],
                    ],
                ],
            ],
            'generationConfig' => [
                'response_mime_type' => 'application/json',
                'temperature' => 0.1,
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $parsed = json_decode($text, true);
            if (is_array($parsed)) {
                $confidence = $parsed['confidence'] ?? [];
                unset($parsed['confidence']);

                return [
                    'status' => 'success',
                    'extracted' => array_filter($parsed),
                    'confidence' => $confidence,
                    'notes' => 'Document analyzed via AI extraction service.',
                ];
            }
        }

        return null;
    }

    /**
     * Google Cloud Vision API text detection.
     */
    protected function extractWithGoogleVision(UploadedFile $file): string
    {
        $apiKey = env('GOOGLE_VISION_KEY');
        if (! $apiKey) {
            return '';
        }

        $base64 = base64_encode(file_get_contents($file->getRealPath()));
        $response = Http::timeout(15)->post("https://vision.googleapis.com/v1/images:annotate?key={$apiKey}", [
            'requests' => [
                [
                    'image' => ['content' => $base64],
                    'features' => [['type' => 'TEXT_DETECTION']],
                ],
            ],
        ]);

        if ($response->successful()) {
            return $response->json('responses.0.fullTextAnnotation.text') ?? '';
        }

        return '';
    }

    /**
     * Check if tesseract command line binary is installed.
     */
    protected function isTesseractAvailable(): bool
    {
        $cmd = PHP_OS_FAMILY === 'Windows' ? 'where tesseract' : 'which tesseract';
        $output = @shell_exec($cmd);

        return ! empty($output);
    }

    /**
     * Tesseract OCR execution.
     */
    protected function extractWithTesseract(UploadedFile $file): string
    {
        $tempPath = $file->getRealPath();
        $outFile = tempnam(sys_get_temp_dir(), 'ocr_');
        $command = 'tesseract '.escapeshellarg($tempPath).' '.escapeshellarg($outFile).' -l ben+eng --oem 1 --psm 3 2>&1';
        @shell_exec($command);

        $resultFile = $outFile.'.txt';
        $text = '';
        if (file_exists($resultFile)) {
            $text = file_get_contents($resultFile);
            @unlink($resultFile);
        }
        @unlink($outFile);

        return (string) $text;
    }

    /**
     * Basic text extraction from PDF files.
     */
    protected function extractFromPdf(UploadedFile $file): string
    {
        $content = @file_get_contents($file->getRealPath());
        if (! $content) {
            return '';
        }

        // Clean simple stream objects from PDF
        if (preg_match_all('/\((.*?)\)\s*Tj/s', $content, $matches)) {
            return implode(' ', $matches[1]);
        }

        return '';
    }

    /**
     * Fast cloud OCR extraction using OCR.space (supports Bengali & English).
     */
    protected function extractWithOcrSpace(UploadedFile $file, string $docType): string
    {
        try {
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $mime = $file->getClientMimeType() ?: 'image/jpeg';
            $lang = in_array($docType, ['nid_front', 'nid_back', 'nid'], true) ? 'ben' : 'eng';

            $response = Http::timeout(10)->asForm()->post('https://api.ocr.space/parse/image', [
                'apikey' => env('OCR_SPACE_KEY', 'helloworld'),
                'base64Image' => "data:{$mime};base64,{$base64}",
                'language' => $lang,
                'isOverlayRequired' => 'false',
                'detectOrientation' => 'true',
                'scale' => 'true',
                'OCREngine' => '2',
            ]);

            $text = '';
            if ($response->successful()) {
                $json = $response->json();
                if (! ($json['IsErroredOnProcessing'] ?? false)) {
                    foreach ($json['ParsedResults'] ?? [] as $pr) {
                        $text .= ($pr['ParsedText'] ?? '')."\n";
                    }
                }
            }

            // For NID front, run quick English pass if English Name wasn't picked up
            if ($docType === 'nid_front' && ! str_contains(strtolower($text), 'name')) {
                $engResponse = Http::timeout(8)->asForm()->post('https://api.ocr.space/parse/image', [
                    'apikey' => env('OCR_SPACE_KEY', 'helloworld'),
                    'base64Image' => "data:{$mime};base64,{$base64}",
                    'language' => 'eng',
                    'isOverlayRequired' => 'false',
                    'detectOrientation' => 'true',
                    'scale' => 'true',
                    'OCREngine' => '1',
                ]);
                if ($engResponse->successful()) {
                    $engJson = $engResponse->json();
                    foreach ($engJson['ParsedResults'] ?? [] as $epr) {
                        $text .= "\n".($epr['ParsedText'] ?? '');
                    }
                }
            }

            return trim($text);
        } catch (\Throwable $e) {
            Log::warning('DocumentExtractionService OCR.space exception: '.$e->getMessage());

            return '';
        }
    }

    /**
     * Regular expression parsing and heuristic field mapping for Bangladeshi NID and certificates.
     */
    public function parseExtractedText(string $rawText, string $docType): array
    {
        $extracted = [];
        $confidence = [];

        // 1. Normalize Bengali numerals to English numerals
        $bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $enDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $normalizedText = str_replace($bnDigits, $enDigits, $rawText);
        $cleanText = preg_replace('/[ \t]+/', ' ', $normalizedText);
        $lines = array_values(array_filter(array_map('trim', explode("\n", $cleanText))));

        if ($docType === 'nid_front' || $docType === 'nid') {
            // NID Number patterns: 10 digit smart card or 13/17 digit traditional card
            if (preg_match('/(ID\s*NO|NID|National\s*ID|Card\s*NO|আইডি\s*নং|নং)[\s\:\#\.\-]*([0-9]{10,17})/iu', $cleanText, $m)) {
                $extracted['nid_number'] = $m[2];
                $confidence['nid_number'] = 0.95;
            } elseif (preg_match('/\b(NID|National\s*ID|ID\s*NO|Card\s*NO|আইডি\s*নং|নং)?[^\d]*([0-9]{10}|[0-9]{13}|[0-9]{17})\b/iu', $cleanText, $m)) {
                $extracted['nid_number'] = $m[2];
                $confidence['nid_number'] = 0.95;
            } elseif (preg_match('/\b([0-9]{3,4}\s+[0-9]{3,4}\s+[0-9]{3,4})\b/', $cleanText, $m)) {
                $num = preg_replace('/\s+/', '', $m[1]);
                if (in_array(strlen($num), [10, 13, 17], true)) {
                    $extracted['nid_number'] = $num;
                    $confidence['nid_number'] = 0.90;
                }
            } elseif (preg_match('/\b[0-9]{10}\b/', $cleanText, $m)) {
                $extracted['nid_number'] = $m[0];
                $confidence['nid_number'] = 0.85;
            }

            // Date of Birth: YYYY-MM-DD or DD Month YYYY or DD/MM/YYYY
            if (preg_match('/\b(DOB|Date\s*of\s*Birth|Date|Birth|জন্ম\s*তারিখ)[^\d]*([0-9]{1,2})[\s\.\-\/]([0-9]{1,2}|Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|[a-zA-Z]+)[\s\.\-\/]([0-9]{4})\b/iu', $cleanText, $m)) {
                $month = $m[3];
                if (! is_numeric($month)) {
                    $mTime = strtotime("1 {$month} 2000");
                    $month = $mTime ? date('m', $mTime) : '01';
                }
                $dob = sprintf('%04d-%02d-%02d', (int) $m[4], (int) $month, (int) $m[2]);
                if (strtotime($dob)) {
                    $extracted['date_of_birth'] = $dob;
                    $confidence['date_of_birth'] = 0.90;
                }
            } elseif (preg_match('/\b([0-9]{1,2})[\s\.\-\/]([0-9]{1,2})[\s\.\-\/](19[5-9][0-9]|200[0-9])\b/', $cleanText, $m)) {
                $dob = sprintf('%04d-%02d-%02d', (int) $m[3], (int) $m[2], (int) $m[1]);
                if (strtotime($dob)) {
                    $extracted['date_of_birth'] = $dob;
                    $confidence['date_of_birth'] = 0.85;
                }
            }

            // Name in English
            if (preg_match('/Name\s*[:\-\.]?\s*([A-Za-z\.\s\-\']{3,60})/i', $rawText, $m)) {
                $name = trim($m[1]);
                $nameParts = preg_split('/\b(Date|DOB|Father|Mother|ID|Birth|National|Republic)\b/i', $name);
                $name = trim(preg_replace('/[^A-Za-z\.\s\-]/', '', $nameParts[0]));
                if (strlen($name) >= 3 && ! preg_match('/^(National|Government|People|Republic)$/i', $name)) {
                    $extracted['name'] = $name;
                    $confidence['name'] = 0.90;
                }
            }
            if (empty($extracted['name'])) {
                foreach ($lines as $idx => $line) {
                    if (preg_match('/^Name\b/i', $line)) {
                        $val = trim(preg_replace('/^Name\s*[:\-\.]?\s*/i', '', $line));
                        $valParts = preg_split('/\b(Date|DOB|Father|Mother|ID|Birth)\b/i', $val);
                        $val = trim($valParts[0]);
                        if (strlen($val) > 2 && ! preg_match('/(National|Government|People|Republic)/i', $val)) {
                            $extracted['name'] = $val;
                            $confidence['name'] = 0.88;
                            break;
                        } elseif (isset($lines[$idx + 1]) && preg_match('/^[A-Z\s\.\-]{3,40}$/', $lines[$idx + 1])) {
                            $extracted['name'] = trim($lines[$idx + 1]);
                            $confidence['name'] = 0.85;
                            break;
                        }
                    }
                }
            }

            // Name in Bangla
            if (preg_match('/নাম\s*[:\-\.]?\s*([\x{0980}-\x{09FF}\s\.\'\-]{3,60})/u', $rawText, $m)) {
                $nameBn = trim($m[1]);
                if (! str_contains($nameBn, 'গণপ্রজাতন্ত্রী') && ! str_contains($nameBn, 'বাংলাদেশ')) {
                    $extracted['name_bn'] = $nameBn;
                    $confidence['name_bn'] = 0.88;
                }
            }
            if (empty($extracted['name_bn'])) {
                foreach ($lines as $idx => $line) {
                    if (preg_match('/^নাম\b/u', $line)) {
                        $val = trim(preg_replace('/^নাম\s*[:\-\.]?\s*/u', '', $line));
                        if (mb_strlen($val) > 2 && ! str_contains($val, 'গণপ্রজাতন্ত্রী')) {
                            $extracted['name_bn'] = $val;
                            $confidence['name_bn'] = 0.85;
                            break;
                        }
                    }
                }
            }

            // Gender inference from common Bangladeshi names
            if (! empty($extracted['name'])) {
                if (preg_match('/\b(MST|MOUSUMI|BEGUM|KHATUN|AKTER|AKTERE|FATEMA|ROKSANA|SULTANA|JANNAT|NAZNIN)\b/i', $extracted['name'])) {
                    $extracted['gender'] = 'female';
                } elseif (preg_match('/\b(MD|MOHAMMAD|ISLAM|HOSSAIN|AHMED|RAHMAN|ALI|KHAN)\b/i', $extracted['name'])) {
                    $extracted['gender'] = 'male';
                }
            }
        } elseif ($docType === 'nid_back') {
            // Blood Group
            if (preg_match('/\b(A|B|AB|O)[\s]*[\+\-\(]+(ve|Positive|Negative|\+|\-|\))\b/i', $normalizedText, $m)) {
                $extracted['blood_group'] = strtoupper(trim($m[0]));
                $confidence['blood_group'] = 0.90;
            } elseif (preg_match('/(রক্তের\s*গ্রুপ|Blood\s*Group)[\s\:\-\.]*([A-Za-z\+\-]+)/ui', $normalizedText, $m)) {
                $extracted['blood_group'] = trim($m[2]);
                $confidence['blood_group'] = 0.90;
            }

            // Permanent Address (ঠিকানা: বাসা/হোল্ডিং, গ্রাম/রাস্তা, ডাকঘর, উপজেলা, জেলা)
            if (preg_match('/(ঠিকানা|Permanent\s*Address|Address)[\s\:\-\.]*(.*?)(\n\n|Blood|রক্তের|প্রদানের|Date\s*of\s*Issue|Sign|$)/uis', $rawText, $m)) {
                $addr = trim(preg_replace('/\s+/', ' ', $m[2]));
                if (strlen($addr) > 5) {
                    $extracted['permanent_address'] = $addr;
                    $confidence['permanent_address'] = 0.90;
                }
            }
            if (empty($extracted['permanent_address'])) {
                $addrLines = [];
                foreach ($lines as $line) {
                    if (preg_match('/(বাসা|হোল্ডিং|গ্রাম|রাস্তা|পোস্ট|ডাকঘর|উপজেলা|থানা|জেলা|Holding|Village|Vill|Road|Post|PO|Thana|Upazila|District|Dist)/ui', $line)) {
                        $addrLines[] = $line;
                    }
                }
                if (! empty($addrLines)) {
                    $extracted['permanent_address'] = implode(', ', $addrLines);
                    $confidence['permanent_address'] = 0.85;
                }
            }
        } elseif ($docType === 'academic') {
            // Qualification / Degree
            if (preg_match('/\b(B\.?Sc\s+in\s+Nursing|Diploma\s+in\s+Nursing|SSC|HSC|Dakhil|Alim|Bachelor|Masters|BBA|BA|B\.Sc)\b/i', $rawText, $m)) {
                $extracted['education_qualification'] = $m[0];
                $confidence['education_qualification'] = 0.88;
            }

            // Passing year
            if (preg_match('/\b(20[0-2][0-9]|199[0-9]|198[0-9])\b/', $normalizedText, $m)) {
                $extracted['education_passing_year'] = (int) $m[0];
                $confidence['education_passing_year'] = 0.85;
            }

            // Institution
            if (preg_match('/(College|University|School|Institute|Academy|বিশ্ববিদ্যালয়|কলেজ)[a-zA-Z\s\x{0980}-\x{09FF}]{3,40}/iu', $rawText, $m)) {
                $extracted['education_institution'] = trim($m[0]);
                $confidence['education_institution'] = 0.80;
            }
        } elseif ($docType === 'certificate') {
            // Certificate Title
            if (preg_match('/(Certificate|Diploma|Course|Training|Fellowship|Nurse|Caregiver|Specialist)[a-zA-Z\s]{3,40}/i', $rawText, $m)) {
                $extracted['cert_name'] = trim($m[0]);
                $confidence['cert_name'] = 0.82;
            }

            // Certificate Number
            if (preg_match('/(Reg|Certificate|Serial|No|Number)[\s\:\#\.\-]+([A-Za-z0-9\-\/]{4,20})/i', $rawText, $m)) {
                $extracted['cert_number'] = trim($m[2]);
                $confidence['cert_number'] = 0.85;
            }

            // Year
            if (preg_match('/\b(20[0-2][0-9])\b/', $normalizedText, $m)) {
                $extracted['cert_year'] = (int) $m[0];
                $confidence['cert_year'] = 0.85;
            }
        }

        return [
            'status' => ! empty($extracted) ? 'success' : 'manual_review',
            'extracted' => $extracted,
            'confidence' => $confidence,
            'notes' => ! empty($extracted)
                ? 'Extracted matching fields from document text.'
                : 'No high-confidence text matches found. Please complete fields manually.',
        ];
    }
}
