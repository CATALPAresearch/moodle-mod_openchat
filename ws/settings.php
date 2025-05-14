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
class mod_openchat_settings extends external_api {


    /**
     * Load Plugin Settings.
     */
    public static function loadsettings_parameters() {
        return new external_function_parameters(
            [
                'cmid' => new external_value(PARAM_INT, 'id of openchat'),
            ]
        );
    }

    /**
     * Something.
     */
    public static function loadsettings($cmid) {
        global $USER, $DB;
        try {
            $cm = get_coursemodule_from_id('openchat', $cmid, 0, false, MUST_EXIST);
            $openchat = $DB->get_record('openchat', ['id' => $cm->instance], '*', MUST_EXIST);
            $course = $openchat->course;
            $permission = new mod_openchat\permission\course($USER->id, $course);
            if ($permission->isEnrolled() === false && $permission->isAnyKindOfModerator() === false) {
                return ['success' => false, 'data' => get_string('nopermission', 'mod_openchat')];
            }
            return ['success' => true, 'data' => json_encode($openchat)];
        } catch (Exception $ex) {
            return ['success' => false, 'data' => get_string('unknown_error', 'mod_openchat')];
        }
    }

    /**
     * Something.
     */
    public static function loadsettings_returns() {
        return new external_single_structure(
            [
                'success' => new external_value(PARAM_BOOL, 'data'),
                'data' => new external_value(PARAM_RAW, 'data'),
            ]
        );
    }

    /**
     * Something.
     */
    public static function loadsettings_is_allowed_from_ajax() {
        return true;
    }


    /**
     * Update Plugin Settings
     */
    public static function updatesettings_parameters() {
        return new external_function_parameters(
           [
               'cmid' => new external_value(PARAM_INT, 'id of openchat'),
               'settings' => new external_value(PARAM_RAW, 'data'),
           ]
        );
    }

    /**
     * Something.
     */
    public static function updatesettings($cmid, $settings) {
        global $USER, $DB;
        try {
            $cm = get_coursemodule_from_id('openchat', $cmid, 0, false, MUST_EXIST);
            $params = json_decode($settings, true);
            $params['id'] = $cm->instance;
            $openchat = $DB->update_record('openchat', $params);

            return ['success' => true];
        } catch (Exception $ex) {
            return ['success' => false];
        }
    }

    /**
     * Something.
     */
    public static function updatesettings_returns() {
        return new external_single_structure(
            [
                'success' => new external_value(PARAM_BOOL, 'data'),
            ]
        );
    }

    /**
     * Something.
     */
    public static function updatesettings_is_allowed_from_ajax() {
        return true;
    }
}
