<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_openchat_upgrade($oldversion = 0) {
    global $CFG, $DB;
    $dbman = $DB->get_manager();

    $newversion = 2022100114;
    if ($oldversion < $newversion) {

        // Define field id to be added to longpage_reading_progress.
        $table = new xmldb_table('openchat_log');
        $field1 = new xmldb_field('duration', XMLDB_TYPE_INTEGER, '10', null, null, null, null, null);

        // Conditionally launch add field id.
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        
        // Longpage savepoint reached.
        upgrade_plugin_savepoint(true,  $newversion, 'mod', 'openchat');
    }

    return true;
}
