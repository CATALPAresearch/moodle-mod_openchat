<?php
require('../../config.php');
require_once($CFG->dirroot . '/mod/openchat/lib.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->libdir . '/formslib.php');

// Parameters.
$id      = optional_param('id', 0, PARAM_INT); // Course Module ID.
$p       = optional_param('p', 0, PARAM_INT);  // Page instance ID.

// Get module and course information.
if ($p) {
    $page = $DB->get_record('openchat', ['id' => $p], '*', MUST_EXIST);
    $coursemodule = get_coursemodule_from_instance('openchat', $page->id, $page->course, false, MUST_EXIST);
} else if ($id) {
    $coursemodule = get_coursemodule_from_id('openchat', $id, 0, false, MUST_EXIST);
    $page = $DB->get_record('openchat', ['id' => $coursemodule->instance], '*', MUST_EXIST);
} else {
    throw new moodle_exception('missingparameter');
}

$course = $DB->get_record('course', ['id' => $coursemodule->course], '*', MUST_EXIST);

// Setup context and permissions.
require_course_login($course, true, $coursemodule);
$context = context_module::instance($coursemodule->id);
require_capability('mod/openchat:view', $context);

// Determine if the user is an admin/moderator.
$permission = new \mod_openchat\permission\course($USER->id, $course->id);
$isadmin = $permission->isAnyKindOfModerator(true);

// Set up page info.
$PAGE->set_url('/mod/openchat/view.php', ['id' => $coursemodule->id]);
$PAGE->set_title(format_string($page->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// Load JavaScript (AMD, Vue) app.
$PAGE->requires->js_call_amd('mod_openchat/app-lazy', 'initOpenChat', [
    'coursemoduleid' => $coursemodule->id,
    'contextid' => $context->id,
    'isAdmin' => $isadmin,
    'page_instance_id' => $page->id, // use actual instance ID
]);

// Output page.
echo $OUTPUT->header();
echo html_writer::div('', 'OpenChatApp', ['id' => 'OpenChatApp']);
echo $OUTPUT->footer($course);

// Logging
\mod_openchat\event\course_module_viewed::create([
    'objectid' => $page->id,
    'context' => $context,
])->trigger();

