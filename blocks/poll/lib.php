<?php

require_once('db/poll_repository.php');

function block_poll_options() {
    return [
        // id => option
        1 => 'OPT 1',
        2 => 'OPT 2',
        3 => 'OPT 3',
    ];
}

function block_poll_print_page($poll, $return = false) {
    global $OUTPUT, $COURSE;
    $display = $OUTPUT->heading($poll->title);

    $display .= $OUTPUT->box_start();

    $display .= $poll->question;

    if($return) {
        return $display;
    } else {
        echo $display;
    }
}

function create_poll($fromform) {
    global $USER;

    $repository = new poll_repository();

    $fromform->userid = $USER->id;
    $pollId = $repository->create_poll($fromform);

    create_options($pollId, $fromform);
}

function create_options($pollId, $fromform) {
    $repository = new poll_repository();

    $option1 = build_option($pollId, $fromform->option_1);
    $option2 = build_option($pollId, $fromform->option_2);
    $option3 = build_option($pollId, $fromform->option_3);

    $repository->create_option($option1);
    $repository->create_option($option2);
    $repository->create_option($option3);
}

function build_option($pollId, $desc) {
    $option = new stdClass();
    $option->pollid = $pollId;
    $option->description = $desc;

    return $option;
}