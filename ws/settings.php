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
            // Get user settings of the local instance of the plugin.
            $cm = get_coursemodule_from_id('openchat', $cmid, 0, false, MUST_EXIST);
            $openchat = $DB->get_record('openchat', ['id' => $cm->instance], '*', MUST_EXIST);
            $course = $openchat->course;
            $permission = new mod_openchat\permission\course($USER->id, $course);
            if ($permission->isEnrolled() === false && $permission->isAnyKindOfModerator() === false) {
                return ['success' => false, 'data' => get_string('nopermission', 'mod_openchat')];
            }
            // Get selected global settings of the plugin. API keys will not be transfered to the client for security reasons
            $admin_settings = [
                'llm' => [
                    'llmenabled' => get_config('mod_openchat', 'enable_llm'),
                    'llmhostname' => get_config('mod_openchat', 'llm_host'),
                    //'llmapiKey' => get_config('mod_openchat', 'llm_apikey'),
                ],
                'rag' => [
                    'ragenabled' => get_config('mod_openchat', 'enable_rag'),
                    'raghostname' => get_config('mod_openchat', 'rag_webservice_host'),
                    //'RAGapiKey' => get_config('mod_openchat', 'rag_webservice_apikey'),
                ],
                'agent' => [
                    'agentenabled' => get_config('mod_openchat', 'enable_agent'),
                    'agenthostname' => get_config('mod_openchat', 'agent_webservice_host'),
                    //'agentapiKey' => get_config('mod_openchat', 'agent_webservice_apikey'),
                ],
            ];
            // Combine and return all settings.
            $openchat_data = (array) $openchat;
            $all_settings = array_merge($openchat_data, $admin_settings);
            return [
                'success' => true, 
                'data' => json_encode($all_settings)
            ];
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
        global $DB;
        try {
            $settings = json_decode($settings, true);
            $cm = get_coursemodule_from_id('openchat', $cmid, 0, false, MUST_EXIST);
            $params = []; 
            $params['id'] = $cm->instance;
            // We cannot just send all settings back to database because some settings are derived from the plugin settings and not from the plugin instance settings.
            //$params['name'] = $settings['name'];
            $params['intro'] = $settings['intro'];
            $params['model'] = $settings['model'];
            $params['chatmodus'] = $settings['chatmodus'];
            $params['prompttemplate'] = $settings['prompttemplate'];

            $DB->update_record('openchat', $params);

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
