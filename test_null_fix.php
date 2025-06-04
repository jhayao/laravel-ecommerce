<?php

// Test the null coalescing fix
$testData = null;
$result = $testData ?? [];

echo "Testing null coalescing operator:\n";
echo "Original value: " . var_export($testData, true) . "\n";
echo "After ?? []: " . var_export($result, true) . "\n";
echo "Type: " . gettype($result) . "\n";

if (is_array($result)) {
    echo "✅ SUCCESS: Result is an array as expected\n";
} else {
    echo "❌ FAILED: Result is not an array\n";
}

// Test with actual request-like scenario
function mockRequestInput($key, $default = null) {
    // Simulate request input that might return null
    $data = ['title' => 'Test', 'message' => 'Test message'];
    return isset($data[$key]) ? $data[$key] : null;
}

$dataParam = mockRequestInput('data') ?? [];
echo "\nTesting request input simulation:\n";
echo "Data parameter: " . var_export($dataParam, true) . "\n";
echo "Type: " . gettype($dataParam) . "\n";

if (is_array($dataParam)) {
    echo "✅ SUCCESS: Data parameter is an array as expected\n";
} else {
    echo "❌ FAILED: Data parameter is not an array\n";
}
