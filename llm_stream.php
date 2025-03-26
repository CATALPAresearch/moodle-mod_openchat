<?php
//curl -X POST http://localhost/moodle413/mod/openchat/llm_stream.php -d "model=phi3:latest" -d "prompt=Hello world"  -d "hostname=http://localhost:11434/api/generate"

//require_login();

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

$model = "phi3:latest";
$prompt = "Was ist grün?";
$hostname = "http://localhost:11434/";

if(isset($_POST['model']) && isset($_POST['prompt']) && isset($_POST['hostname'])){
    $model = $_POST['model'];
    $prompt = $_POST['prompt'];
    $hostname = $_POST['hostname'];    
}

$apiKey = "";

$ch = curl_init($hostname . 'api/generate');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey,
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => $model,
    'prompt' => $prompt,
    'stream' => true,
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) {
    echo $data; // Stream the data chunk to the client
    //echo "data: " . trim($data) . "\n\n";
    ob_flush();
    flush();
    return strlen($data);
});

curl_exec($ch);

if (curl_errno($ch)) {
    echo "Error: " . curl_error($ch);
}

curl_close($ch);
