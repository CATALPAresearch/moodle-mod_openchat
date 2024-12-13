<?php
require('../../config.php');
require_once($CFG->dirroot . '/mod/openchat/lib.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->libdir . '/formslib.php');

require_login();

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

$model = $_POST['model'];
$prompt = $_POST['prompt'];
$hostname = $_POST['hostname'];
$id = $_POST['coursemoduleid'];
$p = $_POST['pageinstanceid'];

if ($p) {
    if (!$page = $DB->get_record('openchat', array('id' => $p))) {
        print_error('invalidaccessparameter', 'error', $CFG->wwwroot);
    }
    $cm = get_coursemodule_from_instance('openchat', $page->id, $page->course, false, MUST_EXIST);
} else {
    if (!$cm = get_coursemodule_from_id('openchat', $id)) {
        print_error('invalidcoursemodule', 'error', $CFG->wwwroot);
    }
    $page = $DB->get_record('openchat', array('id' => $cm->instance), '*', MUST_EXIST);
}

$apiKey = $page->apikey;

$ch = curl_init($hostname);
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
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) {
    echo $data; // Stream the data chunk to the client
    ob_flush();
    flush();
    return strlen($data);
});

curl_exec($ch);

if (curl_errno($ch)) {
    echo "Error: " . curl_error($ch);
}

curl_close($ch);
