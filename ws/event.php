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
require_once($CFG->dirroot . '/group/lib.php');

/**
 * Something.
 */
class mod_openchat_event extends external_api {

    /**
     * Something.
     */
    public static function triggerevent_parameters() {
        return new external_function_parameters(
            [
                'cmid' => new external_value(PARAM_INT, 'id of openchat'),
                'action' => new external_value(PARAM_TEXT, 'Name of the performed action', VALUE_OPTIONAL),
                'value' => new external_value(PARAM_TEXT, 'Values related to the action', VALUE_OPTIONAL),
            ]
        );
    }

    // Function to get replies between users in a forum.
    /**
     * Something.
     */
    public static function triggerevent($cmid, $action, $value) {
        try {
            global $USER, $DB;
            $cm = get_coursemodule_from_id('openchat', $cmid, 0, false, MUST_EXIST);
            $openchat = $DB->get_record('openchat', ['id' => $cm->instance], '*', MUST_EXIST);
            $courseid = $openchat->course;

            $payload = [
                'context' => \context_system::instance(),
                'courseid' => $courseid,
                'userid' => $USER->id,
                'other' => serialize($value),
            ];

            switch ($action) {
                case "copy_response":
                    $event = \mod_openchat\event\copy_response_event::create($payload);
                    $event->trigger();
                    break;
                case "llm_response":
                    $event = \mod_openchat\event\llm_response_event::create($payload);
                    $event->trigger();
                    break;
                case "llm_request":
                    $event = \mod_openchat\event\llm_request_event::create($payload);
                    $event->trigger();
                    break;
                case "rag_response":
                    $event = \mod_openchat\event\rag_response_event::create($payload);
                    $event->trigger();
                    break;
                case "rag_request":
                    $event = \mod_openchat\event\rag_request_event::create($payload);
                    $event->trigger();
                    break;
                case "rate_response_negative":
                    $event = \mod_openchat\event\rate_response_negative_event::create($payload);
                    $event->trigger();
                    break;
                case "rate_response_positive":
                    $event = \mod_openchat\event\rate_response_positive_event::create($payload);
                    $event->trigger();
                    break;
                case "view_openchat":
                    $event = \mod_openchat\event\view_openchat_event::create($payload);
                    $event->trigger();
                    break;
            }

            return ['success' => true, 'data' => ''];
        } catch (Exception $ex) {
            return ['success' => false, 'data' => $ex->getMessage()];
        }
    }

    /**
     * Something.
     */
    public static function triggerevent_returns() {
        return new external_single_structure(
            [
                'success' => new external_value(PARAM_BOOL, 'true or false'),
                'data' => new external_value(PARAM_RAW, 'data'),
            ]
        );
    }

    /**
     * Something.
     */
    public static function triggerevent_is_allowed_from_ajax() {
        return true;
    }
}
