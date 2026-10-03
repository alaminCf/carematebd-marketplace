<?php

namespace App\Http\Controllers;

use App\Models\CaregiverCertificate;
use App\Models\CaregiverDocument;
use App\Services\SecureDocumentService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function __construct(
        public SecureDocumentService $documentService
    ) {}

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
