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
 * Status: This Webservice is not implemented since MOODLE doesn't support streaming
 *
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot . "/lib/moodlelib.php");
require_once($CFG->dirroot . '/group/lib.php');

/**
 * Class
 */
class mod_openchat_llm extends external_api {
    /**
     * Upload document.
     */
    public static function llm_request_parameters() {
        return new external_function_parameters([
            'model' => new external_value(PARAM_TEXT, 'Course module ID'),
            'hostname' => new external_value(PARAM_TEXT, 'Base64-encoded file content'),
            'prompt' => new external_value(PARAM_TEXT, 'Filename'),
        ]);
    }

    public static function llm_request($model, $hostname, $prompt) {
        global $USER;


        return ['success' => true];
    }

    public static function llm_request_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Upload success status')
        ]);
    }


    /**
     * Something.
     */
    public static function llm_request_is_allowed_from_ajax() {
        return true;
    }