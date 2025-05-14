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
class mod_openchat_preference extends external_api {


    /**
     * Get/set user preference.
     */
    public static function preference_parameters() {
        return new external_function_parameters(
            [
                'preference' => new external_value(PARAM_TEXT, 'name of openchat preference'),
                'preference_value' => new external_value(PARAM_TEXT, 'value of openchat preference', VALUE_OPTIONAL, 'none'),
            ]
        );
    }

    /**
     * Something.
     */
    public static function preference($preference, $preferencevalue) {
        global $USER;
        try {
            if ($preference != '') {
                $existingpreferencevalue = get_user_preferences('mod_openchat_'.$preference, 'no', $USER->id);
                if ($existingpreferencevalue == null) {
                    set_user_preference('mod_openchat_'.$preference, 'no', $USER->id);
                    return ['success' => true, 'preference' => 'no'];
                }
                if ($preferencevalue == 'none') {
                    return ['success' => true, 'preference' => $existingpreferencevalue];
                } else {
                    set_user_preference('mod_openchat_'.$preference, $preferencevalue, $USER->id);
                    return ['success' => true, 'preference' => $preferencevalue];
                }
            }
            return ['success' => false, 'preference' => 'none'];
        } catch (Exception $ex) {
            return ['success' => false, 'preference' => 'none'];
        }
    }

    /**
     * Something.
     */
    public static function preference_returns() {
        return new external_single_structure(
            [
                'success' => new external_value(PARAM_BOOL, 'data'),
                'preference' => new external_value(PARAM_RAW, 'data', VALUE_OPTIONAL, false),
            ]
        );
    }

    /**
     * Something.
     */
    public static function preference_is_allowed_from_ajax() {
        return true;
    }
}
