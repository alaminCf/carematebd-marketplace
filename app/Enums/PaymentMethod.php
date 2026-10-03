<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum PaymentMethod: string
{
    use HasLabels;

    case Cash = 'cash';
    case BankTransfer = 'bank_transfer';
    case Bkash = 'bkash';
    case Nagad = 'nagad';
    case Rocket = 'rocket';
    case Card = 'card';

    public function label(): string
    {
        return match ($this) {
            self::Bkash => 'bKash',
            self::BankTransfer => 'Bank Transfer',
            default => ucfirst($this->value),
        };
    }
}
