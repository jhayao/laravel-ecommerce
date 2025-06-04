<?php

namespace App\Console\Commands;

use App\Services\OneSignalService;
use Illuminate\Console\Command;

class TestOneSignal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onesignal:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test OneSignal API connection and functionality';

    protected $oneSignalService;

    public function __construct(OneSignalService $oneSignalService)
    {
        parent::__construct();
        $this->oneSignalService = $oneSignalService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing OneSignal API Connection...');
        $this->newLine();

        // Test 1: Configuration check
        $this->info('1. Checking configuration...');
        $appId = config('services.onesignal.app_id');
        $apiKey = config('services.onesignal.rest_api_key');
        $apiUrl = config('services.onesignal.api_url');

        if (empty($appId) || empty($apiKey)) {
            $this->error('OneSignal configuration is missing!');
            return 1;
        }

        $this->info("App ID: {$appId}");
        $this->info("API URL: {$apiUrl}");
        $this->info("API Key: " . substr($apiKey, 0, 8) . "...");
        $this->newLine();

        // Test 2: Get app information
        $this->info('2. Testing API connection by getting app info...');
        try {
            $appInfo = $this->oneSignalService->getApp();
            $this->info('✓ Successfully connected to OneSignal!');
            $this->info("App Name: " . ($appInfo['name'] ?? 'N/A'));
            $this->info("Players: " . ($appInfo['players'] ?? 'N/A'));
            $this->info("Updated At: " . ($appInfo['updated_at'] ?? 'N/A'));
        } catch (\Exception $e) {
            $this->error('✗ Failed to connect to OneSignal API');
            $this->error("Error: " . $e->getMessage());
            return 1;
        }

        $this->newLine();
        $this->info('3. OneSignal service is ready for use!');
        $this->info('Available endpoints:');
        $this->line('  - GET /api/onesignal/test');
        $this->line('  - GET /api/onesignal/app-info');
        $this->line('  - POST /api/onesignal/send/all');
        $this->line('  - POST /api/onesignal/send/segments');
        $this->line('  - POST /api/onesignal/send/player-ids');
        $this->line('  - POST /api/onesignal/send/external-user-ids');

        $this->newLine();
        $this->info('Test HTML interface available at: public/onesignal-api-test.html');

        return 0;
    }
}
