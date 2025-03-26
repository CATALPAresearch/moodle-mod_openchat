<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

$model = $_POST['model'] ?? "phi3:latest";
$prompt = $_POST['prompt'] ?? "Was ist grün?";
$hostname = rtrim($_POST['hostname'] ?? "http://localhost:11434", '/') . "/";

// Create the cURL request to Ollama
$ch = curl_init($hostname . 'api/generate');

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => $model,
    'prompt' => $prompt,
    'stream' => true
]));
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) {
    echo $data;
    @ob_flush();
    @flush();
    return strlen($data);
});

curl_exec($ch);

if (curl_errno($ch)) {
    echo "Error: " . curl_error($ch);
}

curl_close($ch);
?>
