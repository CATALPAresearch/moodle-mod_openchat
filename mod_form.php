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


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

/**
 * Module instance settings form.
 */
class mod_openchat_mod_form extends moodleform_mod {
    /**
     * Something.
     */
    public function definition() {
        global $CFG;
        $mform = $this->_form;

        // General settings like title, task description, and prompt template
        $mform->addElement('text', 'name', get_string('title', 'mod_openchat'), ['size' => '64']);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        $mform->addElement('textarea', 'intro', get_string('intro', 'mod_openchat'), [
            'wrap' => 'virtual',
            'rows' => 5,
            'cols' => 50,
        ]);
        $mform->setType('intro', PARAM_TEXT);

        $mform->addElement('textarea', 'prompttemplate', get_string('prompttemplate', 'mod_openchat'), [
            'wrap' => 'virtual',
            'rows' => 5,
            'cols' => 50,
        ]);
        $mform->setType('prompttemplate', PARAM_TEXT);

        // Standard coursemodule elements.
        $this->standard_coursemodule_elements();

        // Action buttons.
        $this->add_action_buttons();
    }

    /**
     * Something.
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        return $errors;
    }
}
