<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

/**
 * Module instance settings form
 */
class mod_openchat_mod_form extends moodleform_mod {
    public function definition() {
        global $CFG;
        $mform = $this->_form;

        // General settings.
        $mform->addElement('text', 'name', get_string('name', 'mod_openchat'), ['size' => '64']);
        $mform->setType('name', PARAM_CLEANHTML);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        // Standard intro elements.
        $this->standard_intro_elements();

        // Custom fields.
        $mform->addElement('text', 'hostname', get_string('hostname', 'mod_openchat'), ['size' => '64']);
        $mform->setType('hostname', PARAM_TEXT);

        $mform->addElement('text', 'apikey', get_string('apikey', 'mod_openchat'), ['size' => '64']);
        $mform->setType('apikey', PARAM_TEXT);

        $mform->addElement('textarea', 'prompttemplate', get_string('prompttemplate', 'mod_openchat'), 'wrap="virtual" rows="5" cols="50"');
        $mform->setType('prompttemplate', PARAM_TEXT);

        $mform->addElement('text', 'model', get_string('model', 'mod_openchat'), ['size' => '64']);
        $mform->setType('model', PARAM_TEXT);

        // Standard coursemodule elements.
        $this->standard_coursemodule_elements();

        // Action buttons.
        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        /*
        if (!filter_var($data['hostname'], FILTER_VALIDATE_URL)) {
            $errors['hostname'] = get_string('invalidurl', 'mod_openchat');
        }
        if (empty($data['apikey'])) {
            $errors['apikey'] = get_string('required');
        }
        */
        return $errors;
    }
}
