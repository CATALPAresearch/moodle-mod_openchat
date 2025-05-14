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

/**
 *
 * @package    mod_openchat
 * @copyright  2025 Niels Seidel <niels.seidel@fernuni-hagen.de>, CATALPA, FernUniversität Hagen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

require('../../config.php');
require_once($CFG->dirroot . '/mod/openchat/lib.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->libdir . '/formslib.php');

// Parameters.
$id = optional_param('id', 0, PARAM_INT); // Course Module ID.
$p = optional_param('p', 0, PARAM_INT);  // Page instance ID.

// Get module and course information.
$instance = null;

if ($p) {
    $instance = $DB->get_record('openchat', ['id' => $p], '*', MUST_EXIST);
    $coursemodule = get_coursemodule_from_instance('openchat', $instance->id, $instance->course, false, MUST_EXIST);
} else if ($id) {
    $coursemodule = get_coursemodule_from_id('openchat', $id, 0, false, MUST_EXIST);
    $instance = $DB->get_record('openchat', ['id' => $coursemodule->instance], '*', MUST_EXIST);
} else {
    throw new moodle_exception('missingparameter');
}


// Set up page info.
$PAGE->set_url('/mod/openchat/view.php', ['id' => $coursemodule->id]);
$PAGE->set_title(format_string($instance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);


$course = $DB->get_record('course', ['id' => $coursemodule->course], '*', MUST_EXIST);

// Setup context and permissions.
require_course_login($course, true, $coursemodule);

$context = context_module::instance($coursemodule->id);
require_capability('mod/openchat:view', $context);

// Determine if the user is an admin/moderator.
$permission = new \mod_openchat\permission\course($USER->id, $course->id);
$isadmin = $permission->isAnyKindOfModerator(true);

global $SITE;

// Load JavaScript (AMD, Vue) app.
$PAGE->requires->js_call_amd('mod_openchat/app-lazy', 'initOpenChat', [
    'systemName' => $SITE->shortname,
    'courseID' => $course->id,
    'coursemoduleid' => $coursemodule->id,
    'contextid' => $context->id,
    'isAdmin' => $isadmin,
    'page_instance_id' => $page->id, // Use actual instance ID.
    'RAGenabled' => get_config('mod_openchat', 'enable_rag'),
    'RAGhostname' => get_config('mod_openchat', 'rag_webservice_host'),
    'RAGapiKey' => get_config('mod_openchat', 'rag_webservice_apikey'),
]);


// Output page.
$PAGE->activityrecord->intro = '';
echo $OUTPUT->header();
echo html_writer::div('', 'OpenChatApp', ['id' => 'OpenChatApp']);
echo $OUTPUT->footer($course);

$payload = [
    'context' => \context_system::instance(),
    'courseid' => 22,
    'userid' => 22,
    'other' => [
        'value' => 44,
    ],
];
