<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.
// curl -X POST http://localhost/moodle413/mod/openchat/llm_stream.php -d \
// AAAa "model=phi3:latest" -d "prompt=Hello world"  -d "hostname=http://localhost:11434/api/generate"  .

/**
 *
 * @package    mod_openchat
 * @copyright  2025 Niels Seidel <niels.seidel@fernuni-hagen.de>, CATALPA, FernUniversität Hagen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @description To implement streaming (e.g. for real-time chatbot responses from an API like Ollama) within a Moodle plugin webservice, I had to bypass Moodle's normal output buffering and JSON API conventions—because Moodle’s external_api functions are not designed to handle server-sent events (SSE) or chunked streaming output.
 * 
 */

header("Access-Control-Allow-Origin: *"); // or set to Moodle domain
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

$model = required_param('model', 'phi3:latest', PARAM_TEXT);
$prompt = required_param('prompt', "Why didn't you send a prompt?", PARAM_TEXT);
$hostname = optional_param('hostname', 'http://localhost:11434/', PARAM_URL);

$apikey = "";

ob_implicit_flush(true); 
ob_end_flush(); 

$ch = curl_init($hostname . 'api/generate');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apikey,
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
    echo $data; // Stream the data chunk to the client.
    //file_put_contents('debug_post.txt', $data);
    ob_flush();
    flush();
    return strlen($data);
});

curl_exec($ch);

/*
if (curl_errno($ch)) {
    echo "Error: " . curl_error($ch);
    file_put_contents('debug_post.txt', "Error: " . curl_error($ch));
    file_put_contents('debug_post.txt', "Error: " . print_r($ch));
}
*/

curl_close($ch);
