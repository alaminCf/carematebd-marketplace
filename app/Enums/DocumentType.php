<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum DocumentType: string
{
    use HasLabels;

    case NidFront = 'nid_front';
    case NidBack = 'nid_back';
    case CaregivingCertificate = 'caregiving_certificate';
    case NursingCertificate = 'nursing_certificate';
    case TrainingCertificate = 'training_certificate';
    case ExperienceCertificate = 'experience_certificate';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::NidFront => 'NID (Front)',
            self::NidBack => 'NID (Back)',
            default => ucwords(str_replace('_', ' ', $this->value)),
        };
    }

    /**
     * Document types that may be uploaded from the "Documents" step.
     *
     * @return list<self>
     */
    public static function supporting(): array
    {
        return [self::CaregivingCertificate, self::NursingCertificate, self::TrainingCertificate, self::ExperienceCertificate, self::Other];
    }
}
