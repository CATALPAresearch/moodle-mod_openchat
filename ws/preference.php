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

class mod_openchat_preference extends external_api {


    /**
     * Get/set user preference
     */

    public static function preference_parameters(){
        return new external_function_parameters(
            array(
                'preference' => new external_value(PARAM_TEXT, 'name of openchat preference'),
                'preference_value' => new external_value(PARAM_TEXT, 'value of openchat preference', VALUE_OPTIONAL, 'none')
            )
        );
    }
    
    public static function preference($preference, $preference_value){
        global $USER;
        try{
            if($preference != ''){
                $existing_preference_value = get_user_preferences('mod_openchat_'.$preference, 'no', $USER->id);
                if($existing_preference_value == null){
                    set_user_preference('mod_openchat_'.$preference, 'no', $USER->id);
                    return array('success' => true, 'preference' => 'no');    
                }
                if($preference_value == 'none'){
                    return array('success' => true, 'preference' => $existing_preference_value);    
                }else{
                    set_user_preference('mod_openchat_'.$preference, $preference_value, $USER->id);
                    return array('success' => true, 'preference' => $preference_value);
                }
                // unset_user_preference('mod_openchat_'.$preference, $USER->id);
            }
            return array('success' => false, 'preference' => 'none');
        } catch(Exception $ex){
            return array('success' => false, 'preference' => 'none');
        }
    }

    public static function preference_returns(){
        return new external_single_structure(
            array(
                'success' => new external_value(PARAM_BOOL, 'data'),
                'preference' => new external_value(PARAM_RAW, 'data', VALUE_OPTIONAL, false),           
            )
        );
    }

    public static function preference_is_allowed_from_ajax(){
        return true;
    }  

}
