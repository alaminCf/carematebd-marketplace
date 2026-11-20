<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SiteVisit extends Model
{
    protected static bool $tableVerified = false;

    protected $fillable = [
        'visitor_hash',
        'ip_address',
        'user_id',
        'path',
        'route_name',
        'device_type',
        'referrer_source',
        'referrer_url',
        'visited_date',
    ];

    protected function casts(): array
    {
        return [
            'visited_date' => 'date',
        ];
    }

    /**
     * Ensure the site_visits table exists in the database (auto-provisions on shared hosting if migration hasn't run).
     */
    public static function ensureTableExists(): bool
    {
        if (static::$tableVerified) {
            return true;
        }

        try {
            if (! Schema::hasTable('site_visits')) {
                Schema::create('site_visits', function (Blueprint $table): void {
                    $table->id();
                    $table->string('visitor_hash', 64)->index();
                    $table->string('ip_address', 45)->nullable();
                    $table->foreignId('user_id')->nullable()->index();
                    $table->string('path', 255)->index();
                    $table->string('route_name', 120)->nullable()->index();
                    $table->string('device_type', 20)->default('mobile')->index();
                    $table->string('referrer_source', 40)->default('Direct')->index();
                    $table->string('referrer_url', 255)->nullable();
                    $table->date('visited_date')->index();
                    $table->timestamps();
                });
            }

            static::$tableVerified = true;

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Get comprehensive traffic analytics summary for Admin Dashboard & Reports.
     *
     * @return array{
     *     live_now: int,
     *     today_views: int,
     *     today_unique: int,
     *     week_views: int,
     *     week_unique: int,
     *     period_views: int,
     *     period_unique: int,
     *     total_views: int,
     *     total_unique: int,
     *     top_pages: array<int, array{path: string, views: int, unique_visitors: int}>,
     *     devices: array<string, array{count: int, percentage: int}>,
     *     sources: array<string, array{count: int, percentage: int}>,
     *     daily_trend: array<int, array{date: string, label: string, views: int, unique: int}>
     * }
     */
    public static function analyticsSummary(?string $startDate = null, ?string $endDate = null): array
    {
        $empty = [
            'live_now' => 0,
            'today_views' => 0,
            'today_unique' => 0,
            'week_views' => 0,
            'week_unique' => 0,
            'period_views' => 0,
            'period_unique' => 0,
            'total_views' => 0,
            'total_unique' => 0,
            'top_pages' => [],
            'devices' => [
                'mobile' => ['count' => 0, 'percentage' => 0],
                'desktop' => ['count' => 0, 'percentage' => 0],
                'tablet' => ['count' => 0, 'percentage' => 0],
            ],
            'sources' => [
                'Direct' => ['count' => 0, 'percentage' => 0],
                'Google' => ['count' => 0, 'percentage' => 0],
                'Facebook' => ['count' => 0, 'percentage' => 0],
                'WhatsApp' => ['count' => 0, 'percentage' => 0],
                'Other' => ['count' => 0, 'percentage' => 0],
            ],
            'daily_trend' => [],
        ];

        if (! static::ensureTableExists()) {
            return $empty;
        }

        try {
            $today = now()->toDateString();
            $weekAgo = now()->subDays(6)->toDateString();
            $start = $startDate ?: now()->subDays(30)->toDateString();
            $end = $endDate ?: $today;

            $liveNow = (int) static::where('created_at', '>=', now()->subMinutes(15))
                ->distinct('visitor_hash')
                ->count('visitor_hash');

            $todayViews = (int) static::whereDate('visited_date', $today)->count();
            $todayUnique = (int) static::whereDate('visited_date', $today)
                ->distinct('visitor_hash')
                ->count('visitor_hash');

            $weekViews = (int) static::whereBetween('visited_date', [$weekAgo, $today])->count();
            $weekUnique = (int) static::whereBetween('visited_date', [$weekAgo, $today])
                ->distinct('visitor_hash')
                ->count('visitor_hash');

            $periodViews = (int) static::whereBetween('visited_date', [$start, $end])->count();
            $periodUnique = (int) static::whereBetween('visited_date', [$start, $end])
                ->distinct('visitor_hash')
                ->count('visitor_hash');

            $totalViews = (int) static::count();
            $totalUnique = (int) static::distinct('visitor_hash')->count('visitor_hash');

            // Top pages in period
            $topPagesRaw = static::whereBetween('visited_date', [$start, $end])
                ->select('path', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT visitor_hash) as unique_visitors'))
                ->groupBy('path')
                ->orderByDesc('views')
                ->limit(8)
                ->get();

            $topPages = $topPagesRaw->map(fn ($row) => [
                'path' => (string) $row->path,
                'views' => (int) $row->views,
                'unique_visitors' => (int) $row->unique_visitors,
            ])->all();

            // Device breakdown
            $deviceRows = static::whereBetween('visited_date', [$start, $end])
                ->select('device_type', DB::raw('COUNT(*) as cnt'))
                ->groupBy('device_type')
                ->pluck('cnt', 'device_type');

            $devices = [];
            foreach (['mobile', 'desktop', 'tablet'] as $dev) {
                $cnt = (int) ($deviceRows[$dev] ?? 0);
                $devices[$dev] = [
                    'count' => $cnt,
                    'percentage' => $periodViews > 0 ? (int) round(($cnt / $periodViews) * 100) : 0,
                ];
            }

            // Referrer source breakdown
            $sourceRows = static::whereBetween('visited_date', [$start, $end])
                ->select('referrer_source', DB::raw('COUNT(*) as cnt'))
                ->groupBy('referrer_source')
                ->pluck('cnt', 'referrer_source');

            $sources = [];
            foreach (['Direct', 'Google', 'Facebook', 'WhatsApp', 'Other'] as $src) {
                $cnt = (int) ($sourceRows[$src] ?? 0);
                $sources[$src] = [
                    'count' => $cnt,
                    'percentage' => $periodViews > 0 ? (int) round(($cnt / $periodViews) * 100) : 0,
                ];
            }

            // 7-day trend
            $trendRows = static::whereBetween('visited_date', [$weekAgo, $today])
                ->select('visited_date', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT visitor_hash) as uniq'))
                ->groupBy('visited_date')
                ->get()
                ->keyBy(fn ($item) => $item->visited_date instanceof CarbonInterface ? $item->visited_date->toDateString() : substr((string) $item->visited_date, 0, 10));

            $dailyTrend = [];
            for ($i = 6; $i >= 0; $i--) {
                $d = now()->subDays($i);
                $dStr = $d->toDateString();
                $row = $trendRows->get($dStr);
                $dailyTrend[] = [
                    'date' => $dStr,
                    'label' => $d->format('D, d M'),
                    'views' => $row ? (int) $row->views : 0,
                    'unique' => $row ? (int) $row->uniq : 0,
                ];
            }

            return [
                'live_now' => $liveNow,
                'today_views' => $todayViews,
                'today_unique' => $todayUnique,
                'week_views' => $weekViews,
                'week_unique' => $weekUnique,
                'period_views' => $periodViews,
                'period_unique' => $periodUnique,
                'total_views' => $totalViews,
                'total_unique' => $totalUnique,
                'top_pages' => $topPages,
                'devices' => $devices,
                'sources' => $sources,
                'daily_trend' => $dailyTrend,
            ];
        } catch (\Throwable) {
            return $empty;
        }
    }
}
