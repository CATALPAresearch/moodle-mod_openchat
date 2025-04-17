<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) { // Only show settings to site admins
    $settings = new admin_settingpage('mod_openchat', get_string('pluginname', 'mod_openchat'));

    $ADMIN->add('modsettings', $settings);

    $settings->add(new admin_setting_configcheckbox(
        'mod_openchat/enable_llm',
        get_string('enable_llm', 'mod_openchat'),
        get_string('enable_llm_desc', 'mod_openchat'),
        0 // default unchecked
    ));
    $settings->add(new admin_setting_configtext(
        'mod_openchat/llm_host',
        get_string('llm_host', 'mod_openchat'),
        get_string('llm_host_desc', 'mod_openchat'),
        'http://localhost:11434', // default value
        PARAM_TEXT
    ));
    $settings->add(new admin_setting_configtext(
        'mod_openchat/llm_apikey',
        get_string('llm_apikey', 'mod_openchat'),
        get_string('llm_apikey_desc', 'mod_openchat'),
        '', // default value
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configcheckbox(
        'mod_openchat/enable_rag',
        get_string('enable_rag', 'mod_openchat'),
        get_string('enable_rag_desc', 'mod_openchat'),
        0 // default unchecked
    ));
    $settings->add(new admin_setting_configtext(
        'mod_openchat/rag_webservice_host',
        get_string('rag_webservice_host', 'mod_openchat'),
        get_string('rag_webservice_host_desc', 'mod_openchat'),
        'http://localhost:5000', // default value
        PARAM_TEXT
    ));
    $settings->add(new admin_setting_configtext(
        'mod_openchat/rag_webservice_apikey',
        get_string('rag_webservice_apikey', 'mod_openchat'),
        get_string('rag_webservice_apikey_desc', 'mod_openchat'),
        '', // default value
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configcheckbox(
        'mod_openchat/enable_agengt_chat',
        get_string('enable_agent_chat', 'mod_openchat'),
        get_string('enable_agent_chat_desc', 'mod_openchat'),
        0 // default unchecked
    ));
 
}
