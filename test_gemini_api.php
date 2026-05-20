<?php
// Test Gemini API dengan key baru

$apiKey = 'AIzaSyB9uHaVWGNO6kurOdsVpQ4CSD5YORrz7KU';
$baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

$payload = [
    'contents' => [
        [
            'parts' => [
                [
                    'text' => 'Halo, apa nama saya? Jawab dengan singkat.'
                ]
            ]
        ]
    ]
];

$url = $baseUrl . '?key=' . $apiKey;

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json'
    ],
]);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$error = curl_error($curl);
curl_close($curl);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response:\n";
echo $response . "\n";

if ($error) {
    echo "Error: " . $error . "\n";
}

// Parse response
$data = json_decode($response, true);

if ($httpCode === 200 && isset($data['candidates'])) {
    echo "\n✅ API KEY VALID - Test Berhasil!\n";
    echo "Response dari Gemini:\n";
    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
        echo $data['candidates'][0]['content']['parts'][0]['text'] . "\n";
    }
} else {
    echo "\n❌ API KEY GAGAL - Perlu pengecekan!\n";
    if (isset($data['error'])) {
        echo "Error Message: " . $data['error']['message'] . "\n";
    }
}
?>
