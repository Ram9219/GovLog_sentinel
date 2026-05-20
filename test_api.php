<?php
// Test script to send a log via the API

$url = 'http://127.0.0.1:8000/api/agent/logs';
$token = 'some-long-random-secret';

$data = json_encode([
    'action_type' => 'test_event',
    'message' => 'Test message from verification script',
    'severity' => 'info',
    'source' => 'windows_agent_test'
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-Agent-Token: ' . $token,
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status Code: $httpCode\n";
echo "Response: $response\n";

if ($httpCode === 201 || $httpCode === 200) {
    echo "\n✓ SUCCESS: Log was accepted by the API!\n";
} else {
    echo "\n✗ FAILED: API returned error status\n";
}
?>
