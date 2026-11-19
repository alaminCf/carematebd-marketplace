<?php

namespace App\Http\Controllers;

use App\Models\CaregiverCertificate;
use App\Models\CaregiverDocument;
use App\Models\User;
use App\Services\SecureDocumentService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function __construct(
        public SecureDocumentService $documentService
    ) {}

    /**
     * Serve public storage files (e.g. caregiver avatars, service images) when public/storage symlink is absent.
     */
    public function showPublicStorage(string $path): BinaryFileResponse|Response
    {
        $cleanPath = ltrim(str_replace(['..\\', '../'], '', $path), '/');

        abort_if($cleanPath === '' || str_contains($cleanPath, '..'), 404);

        $candidates = [
            storage_path('app/public/'.$cleanPath),
            base_path('../carematebd.com/storage/app/public/'.$cleanPath),
            base_path('../beta.carematebd.com/storage/app/public/'.$cleanPath),
            storage_path('app/private/'.$cleanPath),
            public_path('storage/'.$cleanPath),
            public_path($cleanPath),
        ];

        foreach ($candidates as $fullPath) {
            if (is_file($fullPath) && is_readable($fullPath)) {
                return response()->file($fullPath, [
                    'Cache-Control' => 'public, max-age=604800',
                ]);
            }
        }

        // Graceful SVG avatar fallback if an uploaded avatar file is missing on disk
        if (str_starts_with($cleanPath, 'avatars/') || str_starts_with($cleanPath, 'temp_avatars/')) {
            $user = User::where('avatar_path', $cleanPath)->first();
            $name = trim($user?->name ?? 'Caregiver');
            $words = preg_split('/\s+/', $name) ?: ['C'];
            $initials = mb_strtoupper(mb_substr($words[0], 0, 1).(isset($words[1]) ? mb_substr($words[1], 0, 1) : ''));

            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 160 160">
    <defs>
        <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#0a394a"/>
            <stop offset="100%" stop-color="#0ea5e9"/>
        </linearGradient>
    </defs>
    <rect width="160" height="160" rx="80" fill="url(#bg)"/>
    <text x="50%" y="53%" dominant-baseline="middle" text-anchor="middle" fill="#ffffff" font-family="system-ui, -apple-system, sans-serif" font-size="56" font-weight="700">{$initials}</text>
</svg>
SVG;

            return response($svg, 200, [
                'Content-Type' => 'image/svg+xml',
                'Cache-Control' => 'public, max-age=3600',
            ]);
        }

        abort(404);
    }

    /**
     * Securely stream private caregiver verification document (NID, etc.)
     */
    public function showDocument(string $uuid): StreamedResponse
    {
        $document = CaregiverDocument::where('uuid', $uuid)->firstOrFail();

        abort_unless(
            Auth::check() && $this->documentService->canAccessDocument(Auth::user(), $document),
            403,
            'Unauthorized access to private identity document.'
        );

        return $this->documentService->streamDocument($document);
    }

    /**
     * Securely stream caregiver certificate.
     */
    public function showCertificate(string $uuid): StreamedResponse
    {
        $certificate = CaregiverCertificate::where('uuid', $uuid)->firstOrFail();

        abort_unless(
            Auth::check() && $this->documentService->canAccessCertificate(Auth::user(), $certificate),
            403,
            'Unauthorized access to certificate.'
        );

        return $this->documentService->streamCertificate($certificate);
    }
}
