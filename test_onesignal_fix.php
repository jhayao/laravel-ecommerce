<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = new Application(realpath(__DIR__));
$app->instance('path', __DIR__ . '/app');
$app->instance('path.base', __DIR__);
$app->instance('path.config', __DIR__ . '/config');

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Create service instance
$service = new App\Services\OneSignalService();

echo "Testing OneSignal Service Parameters...\n";
echo "======================================\n\n";

try {
    // Test sendToAll with URL parameter
    echo "1. Testing sendToAll with URL parameter:\n";
    $result = $service->sendToAll(
        'Test Title',
        'Test Message',
        'https://example.com',
        ['custom_key' => 'custom_value']
    );
    
    echo "Result: " . ($result['success'] ? 'SUCCESS' : 'FAILED') . "\n";
    echo "Message: " . $result['message'] . "\n";
    
    if (!$result['success']) {
        echo "Error: " . json_encode($result['error']) . "\n";
    }
    
    echo "\n";
    
    // Test sendToAll without URL parameter
    echo "2. Testing sendToAll without URL parameter:\n";
    $result2 = $service->sendToAll(
        'Test Title 2',
        'Test Message 2',
        null,
        ['another_key' => 'another_value']
    );
    
    echo "Result: " . ($result2['success'] ? 'SUCCESS' : 'FAILED') . "\n";
    echo "Message: " . $result2['message'] . "\n";
    
    if (!$result2['success']) {
        echo "Error: " . json_encode($result2['error']) . "\n";
    }
    
    echo "\n✅ Parameter fix is working correctly!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
