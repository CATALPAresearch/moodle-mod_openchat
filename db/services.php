<?php

defined('MOODLE_INTERNAL') || die();

$functions = array(
    'mod_openchat_getInfo' => array(
        'classname'   => 'mod_openchat_external',
        'methodname'  => 'getInfo',
        'classpath'   => 'mod/openchat/db/external.php',
        'description' => 'Get all users to display.',
        'type'        => 'read',
        'ajax'        => true,
        'loginrequired' => true
    ),
    'mod_openchat_log' => array(
        'classname'   => 'mod_openchat_external',
        'methodname'  => 'log',
        'classpath'   => 'mod/openchat/db/external.php',
        'description' => 'Get all users to display.',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true
    ),
    'mod_openchat_videoprogress' => array(
        'classname'   => 'mod_openchat_external',
        'methodname'  => 'videoprogress',
        'classpath'   => 'mod/openchat/db/external.php',
        'description' => 'Get all users to display.',
        'type'        => 'read',
        'ajax'        => true,
        'loginrequired' => true
    ),
    'mod_openchat_survey' => array(
        'classname'   => 'mod_openchat_external',
        'methodname'  => 'survey',
        'classpath'   => 'mod/openchat/db/external.php',
        'description' => 'Get all users to display.',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true
    )
);
