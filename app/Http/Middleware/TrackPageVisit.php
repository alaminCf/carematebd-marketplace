<?php

namespace App\Http\Middleware;

use App\Models\SiteVisit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageVisit
{
    /**
     * Record public and portal page visits with Country & Region/City geolocation.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            if (! $this->shouldTrack($request, $response)) {
                return $response;
            }

            if (! SiteVisit::ensureTableExists()) {
                return $response;
            }

            $userAgent = strtolower((string) $request->userAgent());
            $ip = $this->extractClientIp($request);
            $today = now()->toDateString();

            $visitorHash = substr(hash('sha256', $ip.'|'.$userAgent.'|'.config('app.key')), 0, 40);
            $path = '/'.ltrim($request->path(), '/');

            $headerHints = $this->extractHeaderGeoHints($request);
            $geo = SiteVisit::resolveGeoFromIp($ip, $headerHints);

            SiteVisit::create([
                'visitor_hash' => $visitorHash,
                'ip_address' => substr($ip, 0, 45),
                'country' => $geo['country'],
                'country_code' => $geo['country_code'],
                'region' => $geo['region'],
                'user_id' => $request->user()?->id,
                'path' => substr($path, 0, 255),
                'route_name' => $request->route()?->getName() ? substr((string) $request->route()->getName(), 0, 120) : null,
                'device_type' => $this->detectDeviceType($userAgent),
                'referrer_source' => $this->detectReferrerSource((string) $request->headers->get('referer'), $request),
                'referrer_url' => $request->headers->get('referer') ? substr((string) $request->headers->get('referer'), 0, 255) : null,
                'visited_date' => $today,
            ]);
        } catch (\Throwable) {
            // Never disrupt page response if analytics logging fails
        }

        return $response;
    }

    protected function extractClientIp(Request $request): string
    {
        foreach (['CF-Connecting-IP', 'X-Real-IP', 'X-Forwarded-For'] as $header) {
            $val = trim((string) $request->headers->get($header, ''));
            if ($val !== '') {
                $firstIp = trim(explode(',', $val)[0]);
                if (filter_var($firstIp, FILTER_VALIDATE_IP)) {
                    return $firstIp;
                }
            }
        }

        return (string) $request->ip();
    }

    /**
     * @return array{country?: string, country_code?: string, region?: string}
     */
    protected function extractHeaderGeoHints(Request $request): array
    {
        $hints = [];

        $countryCode = $request->headers->get('CF-IPCountry')
            ?: $request->headers->get('X-Vercel-IP-Country')
            ?: $request->headers->get('X-Country-Code');

        if ($countryCode && strtoupper($countryCode) !== 'XX') {
            $hints['country_code'] = strtoupper($countryCode);
            if ($hints['country_code'] === 'BD') {
                $hints['country'] = 'Bangladesh';
            }
        }

        $city = $request->headers->get('CF-IPCity')
            ?: $request->headers->get('X-Vercel-IP-City');
        $region = $request->headers->get('CF-Region')
            ?: $request->headers->get('X-Vercel-IP-Country-Region');

        if ($city || $region) {
            $hints['region'] = trim($city.($city && $region && $city !== $region ? ', '.$region : $region));
        }

        return $hints;
    }

    protected function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET') || $response->getStatusCode() !== 200) {
            return false;
        }

        if ($request->ajax() || $request->expectsJson() || $request->prefetch()) {
            return false;
        }

        if ($request->is(
            'admin*',
            'api/*',
            'media/*',
            'storage/*',
            'secure/*',
            'locale/*',
            'up',
            'sitemap.xml',
            'robots.txt',
            'livewire/*'
        )) {
            return false;
        }

        $ua = strtolower((string) $request->userAgent());
        if ($ua === '' || preg_match('/bot|crawl|spider|slurp|mediapartners|facebookexternalhit|lighthouse|pingdom|gtmetrix/i', $ua)) {
            return false;
        }

        return true;
    }

    protected function detectDeviceType(string $userAgent): string
    {
        if (preg_match('/ipad|tablet|kindle|playbook|silk/i', $userAgent)) {
            return 'tablet';
        }

        if (preg_match('/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i', $userAgent)) {
            return 'mobile';
        }

        return 'desktop';
    }

    protected function detectReferrerSource(string $referer, Request $request): string
    {
        $utmSource = strtolower((string) $request->query('utm_source', ''));
        if (str_contains($utmSource, 'facebook') || str_contains($utmSource, 'fb') || $request->has('fbclid')) {
            return 'Facebook';
        }
        if (str_contains($utmSource, 'google') || $request->has('gclid')) {
            return 'Google';
        }
        if (str_contains($utmSource, 'whatsapp') || str_contains($utmSource, 'wa')) {
            return 'WhatsApp';
        }

        if ($referer === '') {
            return 'Direct';
        }

        $refLower = strtolower($referer);
        $host = strtolower($request->getHost());
        if ($host !== '' && str_contains($refLower, $host)) {
            return 'Direct';
        }

        if (str_contains($refLower, 'google.') || str_contains($refLower, 'bing.') || str_contains($refLower, 'yahoo.')) {
            return 'Google';
        }

        if (str_contains($refLower, 'facebook.') || str_contains($refLower, 'fb.com') || str_contains($refLower, 'instagram.') || str_contains($refLower, 'm.me')) {
            return 'Facebook';
        }

        if (str_contains($refLower, 'whatsapp.') || str_contains($refLower, 'wa.me')) {
            return 'WhatsApp';
        }

        return 'Other';
    }
}
