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

if ($hassiteconfig) {
    $settings = new admin_settingpage('mod_openchat', get_string('pluginname', 'mod_openchat'));

    $ADMIN->add('modsettings', $settings);

    $settings->add(new admin_setting_configcheckbox(
        'mod_openchat/enable_llm',
        get_string('enable_llm', 'mod_openchat'),
        get_string('enable_llm_desc', 'mod_openchat'),
        0
    ));
    $settings->add(new admin_setting_configtext(
        'mod_openchat/llm_host',
        get_string('llm_host', 'mod_openchat'),
        get_string('llm_host_desc', 'mod_openchat'),
        'http://localhost:11434',
        PARAM_TEXT
    ));
    $settings->add(new admin_setting_configtext(
        'mod_openchat/llm_apikey',
        get_string('llm_apikey', 'mod_openchat'),
        get_string('llm_apikey_desc', 'mod_openchat'),
        '',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configcheckbox(
        'mod_openchat/enable_rag',
        get_string('enable_rag', 'mod_openchat'),
        get_string('enable_rag_desc', 'mod_openchat'),
        0
    ));
    $settings->add(new admin_setting_configtext(
        'mod_openchat/rag_webservice_host',
        get_string('rag_webservice_host', 'mod_openchat'),
        get_string('rag_webservice_host_desc', 'mod_openchat'),
        'http://localhost:5000',
        PARAM_TEXT
    ));
    $settings->add(new admin_setting_configtext(
        'mod_openchat/rag_webservice_apikey',
        get_string('rag_webservice_apikey', 'mod_openchat'),
        get_string('rag_webservice_apikey_desc', 'mod_openchat'),
        '',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configcheckbox(
        'mod_openchat/enable_agengt_chat',
        get_string('enable_agent_chat', 'mod_openchat'),
        get_string('enable_agent_chat_desc', 'mod_openchat'),
        0
    ));

}
