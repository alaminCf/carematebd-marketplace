<?php

namespace App\Services;

use App\Models\Setting;

class CommissionService
{
    /**
     * Default commission rate percentage (e.g. 15%).
     */
    public function getCommissionRate(): float
    {
        return (float) Setting::get('platform_commission_rate', 15.0);
    }

    /**
     * Calculate financial split for a total service amount.
     *
     * @return array{service_amount: float, commission_rate: float, commission_amount: float, caregiver_amount: float}
     */
    public function calculate(float $serviceAmount, ?float $customRate = null): array
    {
        $rate = $customRate ?? $this->getCommissionRate();
        $commissionAmount = round(($serviceAmount * $rate) / 100, 2);
        $caregiverAmount = round($serviceAmount - $commissionAmount, 2);

        return [
            'service_amount' => $serviceAmount,
            'commission_rate' => $rate,
            'commission_amount' => $commissionAmount,
            'caregiver_amount' => $caregiverAmount,
        ];
    }
}
