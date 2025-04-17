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
require_once($CFG->dirroot . '/group/lib.php');

class mod_openchat_event extends external_api
{

    public static function triggerEvent_parameters()
    {
        return new external_function_parameters(
            array(
                'cmid' => new external_value(PARAM_INT, 'id of openchat'),
                'action' => new external_value(PARAM_TEXT, 'Name of the performed action', VALUE_OPTIONAL),
                'value' => new external_value(PARAM_TEXT, 'Values related to the action', VALUE_OPTIONAL),
            )
        );
    }

    // Function to get replies between users in a forum
    public static function triggerEvent($cmid, $action, $value)
    {
        try {
            global $USER, $DB;
            $cm = get_coursemodule_from_id('openchat', $cmid, 0, false, MUST_EXIST);
            $openchat = $DB->get_record('openchat', array('id' => $cm->instance), '*', MUST_EXIST);
            $course_id = $openchat->course;

            $payload = array(
                'context' => \context_system::instance(),
                'courseid' => $course_id,
                'userid' => $USER->id,
                'other' => serialize($value),
            );

            switch ($action) {
                case "copy_response":
                    $event = \mod_openchat\event\copy_response_event::create($payload);
                    $event->trigger();
                    break;
                case "llm_response":
                    $event = \mod_openchat\event\llm_response_event::create($payload);
                    $event->trigger();
                    break;
                case "llm_request":
                    $event = \mod_openchat\event\llm_request_event::create($payload);
                    $event->trigger();
                    break;
                case "rag_response":
                    $event = \mod_openchat\event\rag_response_event::create($payload);
                    $event->trigger();
                    break;
                case "rag_request":
                    $event = \mod_openchat\event\rag_request_event::create($payload);
                    $event->trigger();
                    break;
                case "rate_response_negative":
                    $event = \mod_openchat\event\rate_response_negative_event::create($payload);
                    $event->trigger();
                    break;
                case "rate_response_positive":
                    $event = \mod_openchat\event\rate_response_positive_event::create($payload);
                    $event->trigger();
                    break;
                case "view_openchat":
                    $event = \mod_openchat\event\view_openchat_event::create($payload);
                    $event->trigger();
                    break;
            }

            return array('success' => true, 'data' => '');
        } catch (Exception $ex) {
            return array('success' => false, 'data' => $ex->getMessage());
        }
    }

    public static function triggerEvent_returns()
    {
        return new external_single_structure(
            array(
                'success' => new external_value(PARAM_BOOL, 'true or false'),
                'data' => new external_value(PARAM_RAW, 'data'),
            )
        );
    }

    public static function triggerEvent_is_allowed_from_ajax()
    {
        return true;
    }
}
