<?php

defined('MOODLE_INTERNAL') || die();

$functions = array(
    
    'mod_openchat_load_settings' => array(
        'classname'   => 'mod_openchat_settings',
        'methodname'  => 'loadSettings',
        'classpath'   => 'mod/openchat/ws/settings.php',
        'description' => 'Get plugin settings.',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true
    ),
    'mod_openchat_update_settings' => array(
        'classname'   => 'mod_openchat_settings',
        'methodname'  => 'updateSettings',
        'classpath'   => 'mod/openchat/ws/settings.php',
        'description' => 'Update plugin settings.',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true
    ),

    'mod_openchat_preference' => array(
        'classname'   => 'mod_openchat_preference',
        'methodname'  => 'preference',
        'classpath'   => 'mod/openchat/ws/preference.php',
        'description' => 'Get plugin preference.',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true
    ),

    'mod_openchat_logging' => array(
        'classname'   => 'mod_openchat_external',
        'methodname'  => 'logging',
        'classpath'   => 'mod/openchat/ws/logging.php',
        'description' => 'Get store log events.',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true
    ),
    'mod_openchat_triggerEvent' => array(
        'classname'   => 'mod_openchat_event',
        'methodname'  => 'triggerEvent',
        'classpath'   => 'mod/openchat/ws/event.php',
        'description' => 'Trigger an event',
        'type'        => 'read',
        'ajax'        => 'write',
        'loginrequired' => true
    ),
);
