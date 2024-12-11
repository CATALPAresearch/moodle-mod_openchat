<?php

// See https://docs.moodle.org/dev/Access_API

defined('MOODLE_INTERNAL') || die();

$capabilities = array(

    'mod/openchat:view' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'guest' => CAP_ALLOW,
            'user' => CAP_ALLOW,
            'frontpage' => CAP_ALLOW
        )
    ),
 

    'mod/openchat:addinstance' => array(
        'riskbitmask' => RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),
        'clonepermissionsfrom' => 'moodle/course:manageactivities'
    )
    
);
