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

/**
 * Upgrade code for mod_openchat.
 *
 * @param int $oldversion the version we are upgrading from.
 */
function xmldb_openchat_upgrade($oldversion = 0) {
    global $CFG, $DB;
    $dbman = $DB->get_manager();
    $time = time();

    // See https://wimski.org/api/3.8/de/dd9/classdatabase__manager.html  .

    $table = new xmldb_table('openchat');
    if ($dbman->table_exists($table)) {
        $time = time();
        $field = new xmldb_field('chatmodus', XMLDB_TYPE_TEXT, '25', null, null, null, 'llm-chat');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $field = new xmldb_field('intro', XMLDB_TYPE_TEXT, null, null, null, null, '');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
    }

    return true;
}
