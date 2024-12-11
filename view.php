<?php

require('../../config.php');
require_once($CFG->dirroot.'/mod/openchat/lib.php');
//require_once($CFG->dirroot.'/mod/openchat/locallib.php');
require_once($CFG->libdir.'/completionlib.php');
require_once("$CFG->libdir/formslib.php");
//header("Access-Control-Allow-Origin: *");

$id      = optional_param('id', 0, PARAM_INT); // Course Module ID
$p       = optional_param('p', 0, PARAM_INT);  // Page instance ID
$inpopup = optional_param('inpopup', 0, PARAM_BOOL);

if ($p) {
    if (!$page = $DB->get_record('openchat', array('id'=>$p))) {
        print_error('invalidaccessparameter');
    }
    $cm = get_coursemodule_from_instance('openchat', $page->id, $page->course, false, MUST_EXIST);

} else {
    if (!$cm = get_coursemodule_from_id('openchat', $id)) {
        print_error('invalidcoursemodule');
    }
    $page = $DB->get_record('openchat', array('id'=>$cm->instance), '*', MUST_EXIST);
}

$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/openchat:view', $context);


echo $OUTPUT->header();

$PAGE->requires->js_call_amd('mod_openchat/app-lazy', 'initOpenChat');


echo <<<'EOT'
<div id="OpenChatApp"></div>
EOT;

echo $OUTPUT->footer($course);

