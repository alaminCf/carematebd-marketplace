<?php

namespace App\Enums\Concerns;

/**
 * Shared helpers for backed status enums (labels, tones, select options).
 */
trait HasLabels
{
    public function label(): string
    {
        return ucwords(str_replace('_', ' ', $this->value));
    }

    /**
     * Visual tone used by the <x-badge> component.
     */
    public function tone(): string
    {
        return 'neutral';
    }

    public function badgeTone(): string
    {
        return $this->tone();
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
