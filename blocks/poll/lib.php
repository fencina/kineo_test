<?php

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
    global $DB;

    $pollId = $DB->insert_record(TABLE_POLLS, $fromform);

    create_options($pollId, $fromform);
}

function create_options($pollId, $fromform) {
    global $DB;

    $option1 = build_option($pollId, $fromform->option_1);
    $DB->insert_record(TABLE_POLL_OPTIONS, $option1);

    $option2 = build_option($pollId, $fromform->option_2);
    $DB->insert_record(TABLE_POLL_OPTIONS, $option2);

    $option3 = build_option($pollId, $fromform->option_3);
    $DB->insert_record(TABLE_POLL_OPTIONS, $option3);
}

function build_option($pollId, $desc) {
    $option = new stdClass();
    $option->pollid = $pollId;
    $option->description = $desc;

    return $option;
}