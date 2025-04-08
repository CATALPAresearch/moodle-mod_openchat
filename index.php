<?php

// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * List of all openchats in course
 *
 * @package mod_openchat
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

$id = required_param('id', PARAM_INT); // course id

$course = $DB->get_record('course', array('id'=>$id), '*', MUST_EXIST);

require_course_login($course, true);
$PAGE->set_pagelayout('incourse');

// Trigger instances list viewed event.
//$event = \mod_openchat\event\course_module_instance_list_viewed::create(array('context' => context_course::instance($course->id)));
//$event->add_record_snapshot('course', $course);
//$event->trigger();

$stropenchat     = get_string('modulename', 'openchat');
$stropenchats    = get_string('modulenameplural', 'mod_openchat');
$strname         = get_string('name');
$strintro        = get_string('moduleintro');
$strlastmodified = get_string('lastmodified');

$PAGE->set_url('/mod/openchat/index.php', array('id' => $course->id));
$PAGE->set_title($course->shortname.': '.$stropenchats);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($stropenchats);
echo $OUTPUT->header();
echo $OUTPUT->heading($stropenchats);
if (!$openchats = get_all_instances_in_course('openchat', $course)) {
    notice(get_string('thereareno', 'moodle', $stropenchats), "$CFG->wwwroot/course/view.php?id=$course->id");
    exit;
}

$usesections = course_format_uses_sections($course->format);

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_index';

if ($usesections) {
    $strsectionname = get_string('sectionname', 'format_'.$course->format);
    $table->head  = array ($strsectionname, $strname, $strintro);
    $table->align = array ('center', 'left', 'left');
} else {
    $table->head  = array ($strlastmodified, $strname, $strintro);
    $table->align = array ('left', 'left', 'left');
}

$modinfo = get_fast_modinfo($course);
$currentsection = '';
foreach ($openchats as $openchat) {
    $cm = $modinfo->cms[$openchat->coursemodule];
    if ($usesections) {
        $printsection = '';
        if ($openchat->section !== $currentsection) {
            if ($openchat->section) {
                $printsection = get_section_name($course, $openchat->section);
            }
            if ($currentsection !== '') {
                $table->data[] = 'hr';
            }
            $currentsection = $openchat->section;
        }
    } else {
        $printsection = '<span class="smallinfo">'.userdate($openchat->timemodified)."</span>";
    }

    $class = $openchat->visible ? '' : 'class="dimmed"'; // hidden modules are dimmed

    $table->data[] = array (
        $printsection,
        "<a $class href=\"view.php?id=$cm->id\">".format_string($openchat->name)."</a>",
        format_module_intro('openchat', $openchat, $cm->id));
}

echo html_writer::table($table);

echo $OUTPUT->footer();

