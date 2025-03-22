<?php

defined('MOODLE_INTERNAL') || die();

$functions = array(
    
    'mod_openchat_settings' => array(
        'classname'   => 'mod_openchat_settings',
        'methodname'  => 'getSettings',
        'classpath'   => 'mod/openchat/ws/settings.php',
        'description' => 'Get plugin settings.',
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
);
