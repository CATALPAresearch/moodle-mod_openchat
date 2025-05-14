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

namespace mod_openchat\event;

/**
 * Something.
 */
class llm_response_event extends \core\event\base {

    /**
     * Something.
     */
    protected function init() {
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
        $this->data['objecttable'] = 'none';
        $this->data['action'] = 'llm_response';
    }

    /**
     * Something.
     */
    public static function get_name() {
        return 'mod_openchat_log';
    }

    /**
     * Something.
     */
    public function get_description() {
        $other = $this->data['other'] ?? [];
        $courseid = $other['courseid'] ?? 'unknown';

        return "The user with id '{$this->userid}' got a response from a LLM" .
            " in the 'openchat activity' with course module id " .
            "'{$this->contextinstanceid}' in course '{$courseid}'.";
    }

    /**
     * Something.
     */
    public function get_url() {
        return new \moodle_url('/mod/openchat/view.php', ['id' => $this->contextinstanceid]);
    }

    /**
     * Something.
     */
    public static function get_objectid_mapping() {
        return ['db' => 'openchat', 'restore' => 'openchat'];
    }
}
