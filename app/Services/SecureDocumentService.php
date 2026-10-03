<?php

namespace App\Services;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use App\Models\Caregiver;
use App\Models\CaregiverCertificate;
use App\Models\CaregiverDocument;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecureDocumentService
{
    /**
     * Upload a private sensitive document (NID, training certificate, etc.)
     */
    public function storePrivateDocument(
        Caregiver $caregiver,
        UploadedFile $file,
        DocumentType $type,
        ?string $title = null
    ): CaregiverDocument {
        $disk = Storage::disk('local');
        $directory = 'private/caregivers/'.$caregiver->id.'/documents';
        $path = $disk->putFile($directory, $file);

        return CaregiverDocument::create([
            'caregiver_id' => $caregiver->id,
            'type' => $type,
            'title' => $title ?? $type->label(),
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize() ?: 0,
            'status' => DocumentStatus::Pending,
        ]);
    }

    /**
     * Upload a certificate. Can be marked public or private.
     */
    public function storeCertificate(
        Caregiver $caregiver,
        UploadedFile $file,
        array $attributes
    ): CaregiverCertificate {
        $disk = Storage::disk('local');
        $directory = 'private/caregivers/'.$caregiver->id.'/certificates';
        $path = $disk->putFile($directory, $file);

        return CaregiverCertificate::create([
            'caregiver_id' => $caregiver->id,
            'name' => $attributes['name'],
            'institution' => $attributes['institution'],
            'certificate_number' => $attributes['certificate_number'] ?? null,
            'issue_year' => $attributes['issue_year'] ?? null,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType() ?: 'application/octet-stream',
            'is_public' => $attributes['is_public'] ?? true,
            'is_verified' => false,
        ]);
    }

    /**
     * Verify whether a user is authorized to download or view a private caregiver document.
     */
    public function canAccessDocument(User $user, CaregiverDocument $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isCaregiver() && $user->caregiver?->id === $document->caregiver_id;
    }

    /**
     * Verify whether a user is authorized to download or view a certificate file.
     */
    public function canAccessCertificate(User $user, CaregiverCertificate $certificate): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isCaregiver() && $user->caregiver?->id === $certificate->caregiver_id) {
            return true;
        }

        // If public certificate, any authenticated user can view
        return $certificate->is_public;
    }

    /**
     * Stream a private document file securely.
     */
    public function streamDocument(CaregiverDocument $document): StreamedResponse
    {
        $disk = Storage::disk('local');
        abort_unless($disk->exists($document->path), 404, 'File not found');

        return response()->stream(function () use ($disk, $document): void {
            $stream = $disk->readStream($document->path);
            if ($stream) {
                fpassthru($stream);
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => $document->mime_type,
            'Content-Disposition' => 'inline; filename="'.$document->original_name.'"',
        ]);
    }

    /**
     * Stream a certificate file securely.
     */
    public function streamCertificate(CaregiverCertificate $certificate): StreamedResponse
    {
        $disk = Storage::disk('local');
        abort_unless($certificate->file_path && $disk->exists($certificate->file_path), 404, 'File not found');

        return response()->stream(function () use ($disk, $certificate): void {
            $stream = $disk->readStream($certificate->file_path);
            if ($stream) {
                fpassthru($stream);
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => $certificate->mime_type ?: 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.($certificate->original_name ?: 'certificate.pdf').'"',
        ]);
    }
}
