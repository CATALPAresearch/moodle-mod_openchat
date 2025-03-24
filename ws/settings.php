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

class mod_openchat_settings extends external_api {


    /**
     * Load Plugin Settings
     */

    public static function loadSettings_parameters(){
        return new external_function_parameters(
            array(
                'cmid' => new external_value(PARAM_INT, 'id of openchat')
            )
        );
    }
    
    public static function loadSettings($cmid){
        global $USER, $DB;
        try{
            $cm = get_coursemodule_from_id('openchat', $cmid, 0, false, MUST_EXIST);
            $openchat = $DB->get_record('openchat', array('id' => $cm->instance), '*', MUST_EXIST);
            $course = $openchat->course;
            $permission = new mod_openchat\permission\course($USER->id, $course);
            if($permission->isEnrolled() === false && $permission->isAnyKindOfModerator() === false){
                return array('success' => false, 'data' => get_string('nopermission', 'mod_openchat'));
            }
            return array('success' => true, 'data' => json_encode($openchat));
        } catch(Exception $ex){
            return array('success' => false, 'data' => get_string('unknown_error', 'mod_openchat'));
        }
    }

    public static function loadSettings_returns(){
        return new external_single_structure(
            array(
                'success' => new external_value(PARAM_BOOL, 'data'),
                'data' => new external_value(PARAM_RAW, 'data'),           
            )
        );
    }

    public static function loadSettings_is_allowed_from_ajax(){
        return true;
    }  


    /**
     * Update Plugin Settings
     */

     public static function updateSettings_parameters(){
        return new external_function_parameters(
            array(
                'cmid' => new external_value(PARAM_INT, 'id of openchat'),
                'settings' => new external_value(PARAM_RAW, 'data'),  
            )
        );
    }
    
    public static function updateSettings($cmid, $settings){
        global $USER, $DB;
        try{
            $cm = get_coursemodule_from_id('openchat', $cmid, 0, false, MUST_EXIST);
            $params = json_decode($settings, true);
            $params['id'] = $cm->instance;
            $openchat = $DB->update_record('openchat', $params);
            /*$course = $openchat->course;
            $permission = new mod_openchat\permission\course($USER->id, $course);
            if($permission->isEnrolled() === false && $permission->isAnyKindOfModerator() === false){
                return array('success' => false);
            }*/
            return array('success' => true);
        } catch(Exception $ex){
            return array('success' => false);
        }
    }

    public static function updateSettings_returns(){
        return new external_single_structure(
            array(
                'success' => new external_value(PARAM_BOOL, 'data')         
            )
        );
    }

    public static function updateSettings_is_allowed_from_ajax(){
        return true;
    }  
}
