<?php
require('../../config.php');
require_once($CFG->dirroot . '/mod/openchat/lib.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->libdir . '/formslib.php');

require_login();

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

$mode = 'normal';

/*
$model = 'deepseek-r1';
$hostname = 'http://localhost:5000/process';
$prompt = 'Was muss ich können?';
$id = 4;
$p=169;

$model = $_GET['model'];
$prompt = $_GET['prompt'];
$hostname = $_GET['hostname'];
$id = $_GET['coursemoduleid'];
$p = $_GET['pageinstanceid'];
*/


$model = $_POST['model'];
$prompt = $_POST['prompt'];
$document_index = $_POST['document_index'];
$filter = $_POST['filter'];
$hostname = "http://localhost:5000/llm/query_documents";//$_POST['hostname']; # FixMe


$id = $_POST['coursemoduleid'];
$p = $_POST['pageinstanceid'];

/*
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
*/


//$post_data = [
    //'model' => $model,
//    'document_index' => $document_index,
//    'prompt' => $prompt,
//];

$post_data = [
    //'model' => $model,
    //'document_index' => json_decode('[ "1c60a5d9-ba87-4ce5-860c-61a6934c46d6", "56928e25-6445-4c4f-a846-b7ef432d5096", "aa38a7b3-4eff-4e3b-9833-28cff5276a22", "c9d6c92d-a478-47f9-9230-d6961271e48c", "6ca2cdf4-b2d9-4f70-a304-a131979c9379", "75667a1d-e316-45c6-896c-ca3424cee115", "0deb785c-a9f3-4cb6-b8e3-ed4972905443", "f4a89367-b405-4758-9a35-3653f38be3bb", "70fdc76b-f45c-4070-811f-49efe13d47f8", "f0f1e065-2de1-4a43-825d-68544bf389c3", "8c83f260-516d-4ec3-b928-e3d40625588b", "52f53737-3051-46ba-88b6-b6a41a7bcb13", "2ec9fd57-da3f-40c1-b404-895e8ef74038", "93321d46-a040-403a-8147-27e09661cf64", "3ff8be30-ce30-4f04-98de-7d55145be9c2", "d111286a-fb4f-4608-9edc-d7cb597f0cda", "b9fbf9e6-b857-4937-b747-bf42cdf2757b", "0dc9d305-38b5-4fdd-abcf-c20c4ea8b2a2", "ed2f6f5f-6458-42fb-a6c5-5a07a4fc3ab7", "193bca49-1b1f-48ed-86ea-e9790c6b7014", "2d84fbc4-1c61-4451-87b7-9b24faa2a2c0", "34755adb-1a6f-456d-8dcc-1dcfdb55d28b" ]'),
    'filter' => $filter,
    'prompt' => "What is a learning theory?",
];



$ch = curl_init($hostname);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
echo $response;


if (curl_errno($ch)) {
    echo "Error: " . curl_error($ch);
}

curl_close($ch);