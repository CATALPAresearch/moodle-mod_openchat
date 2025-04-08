<?php

/**
 *
 * @package    mod_openchat
 * @copyright  2021 Marc Burchart <marc.burchart@fernuni-hagen.de> , Kooperative Systeme, FernUniversität Hagen
 * 
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade code for mod_openchat.
 *
 * @param int $oldversion the version we are upgrading from.
 */
function xmldb_openchat_upgrade($oldversion = 0) {
    global $CFG, $DB;
    $dbman = $DB->get_manager();
    $time = time();

    // https://wimski.org/api/3.8/de/dd9/classdatabase__manager.html

    /**
     * 
     * ===================================================================
     * 
     *  openchat
     * 
     * ===================================================================
     * 
     */

    $table = new xmldb_table('openchat');
    if($dbman->table_exists($table)){
        $time = time();        
        $field = new xmldb_field('chatmodus', XMLDB_TYPE_TEXT, '25', null, null, null, 'llm-chat');
        if(!$dbman->field_exists($table,$field)){ $dbman->add_field($table,$field);}
        $field = new xmldb_field('intro', XMLDB_TYPE_TEXT, null, null, null, null, '');
        if(!$dbman->field_exists($table,$field)){ $dbman->add_field($table,$field);}
        
        // Grouping corrction because of MySQL compatability
        //$field = new xmldb_field('chatmodus', XMLDB_TYPE_INTEGER, '10');
        //if($dbman->field_exists($table, $field)){$dbman->rename_field($table, $field, 'chatmodus');}
    }
    

    return true;
}
