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