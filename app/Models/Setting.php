<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * @var array<string, mixed>|null
     */
    protected static ?array $cachedSettings = null;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    protected static function booted(): void
    {
        static::saved(function (): void {
            static::clearCache();
        });

        static::deleted(function (): void {
            static::clearCache();
        });
    }

    public static function clearCache(): void
    {
        static::$cachedSettings = null;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        if (static::$cachedSettings !== null && array_key_exists($key, static::$cachedSettings)) {
            return static::$cachedSettings[$key];
        }

        $setting = static::where('key', $key)->first();
        if (! $setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    public static function set(string $key, mixed $value, ?string $type = null, ?string $group = null): self
    {
        $encoded = is_array($value) ? json_encode($value) : (string) $value;
        $existing = static::where('key', $key)->first();

        $attributes = [
            'value' => $encoded,
            'type' => $type ?? $existing?->type ?? 'string',
            'group' => $group ?? $existing?->group ?? 'general',
        ];

        $model = static::updateOrCreate(
            ['key' => $key],
            $attributes
        );

        static::clearCache();

        return $model;
    }

    /**
     * @return array{
     *     platform_name: string,
     *     office_address: string,
     *     contact_email: string,
     *     contact_phone: string,
     *     contact_phone_tel: string,
     *     contact_phone_wa: string,
     *     emergency_hotline: string,
     *     emergency_hotline_tel: string
     * }
     */
    public static function contactInfo(): array
    {
        $defaults = static::defaultContactInfo();

        try {
            if (static::$cachedSettings === null) {
                static::$cachedSettings = [];
                foreach (static::all() as $setting) {
                    static::$cachedSettings[$setting->key] = static::castValue($setting->value, $setting->type);
                }
            }

            $platformName = trim((string) (static::$cachedSettings['platform_name'] ?? '')) ?: $defaults['platform_name'];
            $officeAddress = trim((string) (static::$cachedSettings['office_address'] ?? '')) ?: $defaults['office_address'];
            $contactEmail = trim((string) (static::$cachedSettings['contact_email'] ?? '')) ?: $defaults['contact_email'];
            $contactPhone = trim((string) (static::$cachedSettings['contact_phone'] ?? '')) ?: $defaults['contact_phone'];
            $emergencyHotline = trim((string) (static::$cachedSettings['emergency_hotline'] ?? '')) ?: $defaults['emergency_hotline'];
            $googleAnalyticsId = trim((string) (static::$cachedSettings['google_analytics_id'] ?? ''));
            $googleSiteVerification = trim((string) (static::$cachedSettings['google_site_verification'] ?? ''));
            $metaPixelId = trim((string) (static::$cachedSettings['meta_pixel_id'] ?? ''));
            $microsoftClarityId = trim((string) (static::$cachedSettings['microsoft_clarity_id'] ?? ''));
        } catch (\Throwable) {
            return $defaults;
        }

        return [
            'platform_name' => $platformName,
            'office_address' => $officeAddress,
            'contact_email' => $contactEmail,
            'contact_phone' => $contactPhone,
            'contact_phone_tel' => static::formatTelNumber($contactPhone),
            'contact_phone_wa' => static::formatWhatsAppNumber($contactPhone),
            'emergency_hotline' => $emergencyHotline,
            'emergency_hotline_tel' => static::formatTelNumber($emergencyHotline),
            'google_analytics_id' => $googleAnalyticsId,
            'google_site_verification' => $googleSiteVerification,
            'meta_pixel_id' => $metaPixelId,
            'microsoft_clarity_id' => $microsoftClarityId,
        ];
    }

    /**
     * @return array{
     *     platform_name: string,
     *     office_address: string,
     *     contact_email: string,
     *     contact_phone: string,
     *     contact_phone_tel: string,
     *     contact_phone_wa: string,
     *     emergency_hotline: string,
     *     emergency_hotline_tel: string,
     *     google_analytics_id: string,
     *     google_site_verification: string,
     *     meta_pixel_id: string,
     *     microsoft_clarity_id: string
     * }
     */
    public static function defaultContactInfo(): array
    {
        return [
            'platform_name' => 'CareMate BD',
            'office_address' => 'E-14/X, ICT Tower (14th Floor), Agargaon, Dhaka-1207, Bangladesh',
            'contact_email' => 'contact@carematebd.com',
            'contact_phone' => '+880 1610-296460',
            'contact_phone_tel' => '+8801610296460',
            'contact_phone_wa' => '8801610296460',
            'emergency_hotline' => '+880 1610-296460',
            'emergency_hotline_tel' => '+8801610296460',
            'google_analytics_id' => '',
            'google_site_verification' => '',
            'meta_pixel_id' => '',
            'microsoft_clarity_id' => '',
        ];
    }

    protected static function castValue(mixed $value, ?string $type): mixed
    {
        return match ($type) {
            'boolean', 'bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer', 'int' => (int) $value,
            'float', 'decimal' => (float) $value,
            'json', 'array' => json_decode((string) $value, true),
            default => $value,
        };
    }

    protected static function formatTelNumber(string $phone): string
    {
        $cleaned = preg_replace('/[^0-9+]/', '', $phone);

        return $cleaned ?: '+8801610296460';
    }

    protected static function formatWhatsAppNumber(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone) ?: '';

        if (str_starts_with($digits, '01') && strlen($digits) === 11) {
            return '88'.$digits;
        }

        return $digits ?: '8801610296460';
    }
}
