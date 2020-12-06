<?php

defined('MOODLE_INTERNAL') || die('MOODLE_INTERNAL constant not defined');

/**
 * Poll block caps.
 */

$capabilities = [
    'block/poll:myaddinstance' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'user' => CAP_ALLOW,
        ],
        'clonepermissionsfrom' => 'moodle/my:manageblocks',
    ],
    'block/poll:addinstance' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_BLOCK,
        'legacy' => [
            'guest' => CAP_PREVENT,
            'student' => CAP_PREVENT,
            'teacher' => CAP_PREVENT,
            'editingteacher' => CAP_PREVENT,
            'coursecreator' => CAP_PREVENT,
            'manager' => CAP_ALLOW,
        ]
    ],
];