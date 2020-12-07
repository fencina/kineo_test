<?php

require_once('db/poll_repository.php');

function block_poll_options() {
    return [
        'Option 1',
        'Option 2',
        'Option 3',
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

function create_poll($data) {
    global $USER;

    $repository = new poll_repository();

    $data->userid = $USER->id;
    $pollId = $repository->create_poll($data);

    create_options($pollId, $data);
}

function update_poll($poll) {
    $repository = new poll_repository();
    $repository->update_poll($poll);
    update_options($poll->id, $poll);
}

function create_options($pollId, $data) {
    $repository = new poll_repository();

    // TODO add options dynamically. For now only 3 options are allowed. Check blocks/poll/poll_form.php
    for ($i = 0; $i < 3; $i++) {
        $tag = 'option_'.$i;
        $option = build_option($pollId, $data->{$tag}, $tag);
        if (!empty($option->description)) {
            $repository->create_option($option);
        }
    }
}

function update_options($pollId, $data) {
    $repository = new poll_repository();

    $currentOptions = $repository->get_options_for_poll($pollId);

    if (empty($currentOptions)) {
        create_options($pollId, $data);
        return;
    }

    foreach ($currentOptions as $option) {
        if (empty($data->{$option->tag})) {
            $repository->delete_option($option->id);
        }
        else if ($option->description != $data->{$option->tag}) {
            $updatedOption = build_option($pollId, $data->{$option->tag}, $option->tag);
            $updatedOption->id = $option->id;
            $repository->update_option($updatedOption);
        }
    }

    for ($i = 0; $i < 3; $i++) {
        $tag = 'option_'.$i;

        $existentOption = array_filter($currentOptions, function ($option) use ($tag) {
           return $option->tag == $tag;
        });

        if (!$existentOption) {
            $option = build_option($pollId, $data->{$tag}, $tag);
            if (!empty($option->description)) {
                $repository->create_option($option);
            }
        }
    }
};

function build_option($pollId, $desc, $tag) {
    $option = new stdClass();
    $option->pollid = $pollId;
    $option->description = $desc;
    $option->tag = $tag;

    return $option;
}

function answer_poll($data) {
    $repository = new poll_repository();
    $repository->create_answer($data);
}

function has_answered_poll($pollId, $userId) {
    $repository = new poll_repository();
    $answer = $repository->get_answers_for_poll_and_user($pollId, $userId);
    return !empty($answer);
}

function poll_has_answers($pollId) {
    $repository = new poll_repository();
    $answers = $repository->get_answers_for_poll($pollId);

    return !empty($answers);
}

function get_poll_results($pollId) {
    $repository = new poll_repository();
    return $repository->get_poll_results($pollId);
}