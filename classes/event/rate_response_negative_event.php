<?php
namespace mod_openchat\event;

defined('MOODLE_INTERNAL') || die();

class rate_response_negative_event extends \core\event\base {

    protected function init() {
        $this->data['crud'] = 'w';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING; 
        $this->data['objecttable'] = 'none';
        $this->data['action'] = 'rate_response_negative';
    }

    public static function get_name() {
        return 'mod_openchat_log';
    }

    public function get_description() {
        extract($this->data["other"]);
        return "The user with id '$this->userid' rated a response from a LLM or RAG/LLM negatively in the 'openchat activity' with course module id " .
                "'$this->contextinstanceid' in course '$this->courseid'.";
    }

    public function get_url() {
        return new \moodle_url('/mod/openchat/view.php', array('id' => $this->contextinstanceid));
    }

    public static function get_objectid_mapping() {
        return array('db' => 'openchat', 'restore' => 'openchat');
    }
}
