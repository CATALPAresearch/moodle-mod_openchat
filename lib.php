<?php

function openchat_add_instance($data) {
    global $DB;

    $data->timecreated = time();
    $data->timemodified = time();

    $id = $DB->insert_record('openchat', $data);
    return $id;
}


function openchat_update_instance($data) {
    
    global $DB;

    $data->timemodified = time();
    $data->id = $data->instance;

    return $DB->update_record('openchat', $data);
}

function openchat_delete_instance($id) {

    global $DB;

    if(!$DB->get_record('openchat', array('id' => $id))) {
        return false;
    }

    $DB->delete_records('openchat', ['id' => $id]);    
    return true;
}


function openchat_supports($feature) {
    switch ($feature) {
        case FEATURE_GROUPS:
            return false;
        case FEATURE_GROUPINGS:
            return false;
        case FEATURE_MOD_INTRO:
            return false;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        default:
            return null;
    }
}