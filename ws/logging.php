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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot . "/lib/moodlelib.php");

/**
 * Something.
 */
class mod_openchat_logging extends external_api {
    /**
     * Something.
     */
    public static function logging_parameters() {
        return new external_function_parameters(
            [
                'data' =>
                    new external_single_structure(
                        [
                            'courseid' => new external_value(PARAM_INT, 'id of course', VALUE_OPTIONAL),
                            'openchatid' => new external_value(PARAM_INT, 'id of course', VALUE_OPTIONAL),
                            'action' => new external_value(PARAM_TEXT, '..action', VALUE_OPTIONAL),
                            'utc' => new external_value(PARAM_INT, '...utc time', VALUE_OPTIONAL),
                            'entry' => new external_value(PARAM_RAW, 'log data', VALUE_OPTIONAL),
                        ]
                    ),
            ]
        );
    }

    /**
     * Something.
     */
    public static function logging($data) {
        global $DB, $USER;

        $r = new stdClass();
        $r->component = 'mod_openchat';
        $r->eventname = '\mod_openchat\event\course_module_' . $data['action'];
        $r->action = $data['action'];
        $r->target = 'course_module';
        $r->objecttable = 'openchat';
        $r->objectid = 0;
        $r->crud = 'r';
        $r->edulevel = 2;
        $r->contextid = 120;
        $r->contextlevel = 70;
        $r->contextinstanceid = 86;
        $r->userid = $USER->id;
        $r->courseid = (int) $data['courseid'];
        $r->anonymous = 0;
        $r->other = $data['entry'];
        $r->timecreated = $data['utc'];
        $r->origin = 'web';
        $r->ip = $_SERVER['REMOTE_ADDR'];

        try {
            $transaction = $DB->start_delegated_transaction();
            $DB->insert_record("logstore_standard_log", (array) $r);
            $transaction->allow_commit();
        } catch (Exception $e) {
            $transaction->rollback($e);
        }

        $d = json_decode($data['entry']);
        $res = -1;
        $DB->set_debug(true);

        try {
            $transaction2 = $DB->start_delegated_transaction();
            $res = $DB->insert_record('openchat_log', [
                'openchat'  => (int) $data['openchatid'],
                'userid' => $USER->id,
                'course' => (int)$data['courseid'],
                'url' => (String) $d->location->url,
                'context' => (String) $d->value->context,
                'position' => (String) round($d->value->currenttime, 3),
                'actions' => (String) $d->value->action,
                'val' => strval($d->value->values),
                'duration' => round($d->value->duration, 3),
                'timemodified' => (int)$d->utc,
            ]);
            $transaction2->allow_commit();
        } catch (Exception $e) {
            $res = $e;
            $transaction2->rollback($e);
        }

        $DB->set_debug(false);
        return [
            'success' => true,
            'response' => json_encode($res),
        ];
    }

    /**
     * Something.
     */
    public static function logging_returns() {
        return new external_single_structure(
            [
                'success' => new external_value(PARAM_BOOL, ''),
                'response' => new external_value(PARAM_RAW, ''),
            ]
        );
    }

    /**
     * Something.
     */
    public static function logging_is_allowed_from_ajax() {
        return true;
    }
}
