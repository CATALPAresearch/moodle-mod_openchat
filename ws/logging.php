<?php

/**
 *
 * @package    mod_openchat
 * @copyright  2021 Marc Burchart <marc.burchart@tu-dortmund.de> , Kooperative Systeme, FernUniversität Hagen
 * 
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot . "/lib/moodlelib.php");

class mod_openchat_logging extends external_api {

    public static function logging_parameters() {
        return new external_function_parameters(
            array(
                'data' =>
                    new external_single_structure(
                        array(
                            'courseid' => new external_value(PARAM_INT, 'id of course', VALUE_OPTIONAL),
                            'openchatid' => new external_value(PARAM_INT, 'id of course', VALUE_OPTIONAL),
                            'action' => new external_value(PARAM_TEXT, '..action', VALUE_OPTIONAL),
                            'utc' => new external_value(PARAM_INT, '...utc time', VALUE_OPTIONAL),
                            'entry' => new external_value(PARAM_RAW, 'log data', VALUE_OPTIONAL)
                        )
                    )
            )
        );
    }
    public static function logging($data) {
        global $DB, $USER; 

        // TODO: trigger event

        $r = new stdClass();
        $r->component = 'mod_openchat';
        $r->eventname = '\mod_openchat\event\course_module_' . $data['action'];
        $r->action = $data['action'];
        $r->target = 'course_module';
        $r->objecttable = 'openchat';
        $r->objectid = 0;
        $r->crud = 'r';
        $r->edulevel = 2;
        $r->contextid = 120;
        $r->contextlevel = 70;
        $r->contextinstanceid = 86;
        $r->userid = $USER->id;
        $r->courseid = (int) $data['courseid'];
        $r->anonymous = 0;
        $r->other = $data['entry']; // unserialize(json_encode(  // unserialize(json_encode(json_decode($data['entry'])))
        $r->timecreated = $data['utc'];
        $r->origin = 'web';
        $r->ip = $_SERVER['REMOTE_ADDR'];

        try {
            $transaction = $DB->start_delegated_transaction();
            $DB->insert_record("logstore_standard_log", (array) $r);
            $transaction->allow_commit();
        } catch (Exception $e) {
            $transaction->rollback($e);
            error_log("writing video log to logstore failed");
        }
        
        $d = json_decode($data['entry']);
        $res = -1;
        $DB->set_debug(true);
        
        try {
            $transaction2 = $DB->start_delegated_transaction();
            $res = $DB->insert_record('openchat_log', [
                'openchat'  => (int) $data['openchatid'],
                'userid' => $USER->id,
                'course' =>  (int)$data['courseid'],
                'url' => (String) $d->location->url,
                'context' => (String) $d->value->context,
                'position' => (String) round($d->value->currenttime, 3),
                'actions' => (String) $d->value->action,
                'val' => strval($d->value->values), //''.$d->value->values,
                'duration' => round($d->value->duration, 3),
                'timemodified' => (int)$d->utc,
            ]);
            //error_log('xxxxxxxx ' . print_r($d->value->values) . '---'.$d->value->values);
            $transaction2->allow_commit();
        } catch (Exception $e) {
            $res = $e;
            $transaction2->rollback($e);
            error_log("writing video log to openchat log table failed");
        }
        
        $DB->set_debug(false);
        return array(
            'success' => true,
            'response' => json_encode($res)
        );
    }
    public static function logging_returns() {
        //return null;
        return new external_single_structure(
            array(
                'success' => new external_value(PARAM_BOOL, ''),
                'response' => new external_value(PARAM_RAW, '')
            )
        );
    }
    public static function logging_is_allowed_from_ajax(){
        return true;
    }   
}