<?php

namespace App\Console\Commands;

use App\Services\GoogleReviewService;
use Illuminate\Console\Command;

class SyncGoogleReviewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:sync-google {--force : Force sync even if API key not found in settings}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync latest reviews from Google Places API for CareMate BD';

    /**
     * Execute the console command.
     */
    public function handle(GoogleReviewService $googleReviewService): int
    {
        $this->info('Starting Google Reviews synchronization...');

        $result = $googleReviewService->syncFromPlacesApi();

        if ($result['success']) {
            $this->info('✓ '.$result['message']);

            return Command::SUCCESS;
        }

        $this->warn('Notice: '.$result['message']);
        $this->info('Default high-rating verified Google reviews remain active.');

        return Command::SUCCESS;
    }
}
