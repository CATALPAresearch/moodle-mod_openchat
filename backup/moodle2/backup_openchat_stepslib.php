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
 * Backup openchat.
 * @package    mod_openchat
 * @copyright  2025 Niels Seidel <niels.seidel@fernuni-hagen.de>, CATALPA, FernUniversität Hagen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define the complete openchat structure for backup, with file and id annotations
 */
class backup_openchat_activity_structure_step extends backup_activity_structure_step {
    /**
     * Bla.
     */
    protected function define_structure() {

        // To know if we are including userinfo.
        // Bla $userinfo = $this->get_setting_value('userinfo'); .
        // Define each element form the install.xml separated.
        $openchat = new backup_nested_element(
            'openchat',
            ['id'],
            [
                'course',
                'name',
                'intro',
                'hostname',
                'apikey',
                'model',
                'chatmodus',
                'prompttemplate',
                'timecreated',
                'timemodified',
            ]
        );
        // Define sources.
        $openchat->set_source_table('openchat', ['id' => backup::VAR_ACTIVITYID]);

        // Define id annotations: (none).

        // Define file annotations.
        $openchat->annotate_files('mod_openchat', 'intro', null);

        // Return the root element (page), wrapped into standard activity structure.
        return $this->prepare_activity_structure($openchat);
    }
}
