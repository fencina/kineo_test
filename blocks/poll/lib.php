<?php

require_once('db/poll_repository.php');

function block_poll_options() {
    return [
        'Option 1',
        'Option 2',
        'Option 3',
    ];
}

function get_block_content_footer($blockId) {
    global $PAGE;
    $repository = new poll_repository();

    $footer = '';

    if ($poll = $repository->get_poll_by_block($blockId)) {
        $footer =  get_footer_for_existent_poll($poll, $blockId);
    } else if ($PAGE->user_is_editing()) {
        $footer = get_edit_link($blockId);
    }

    return $footer;
}

function get_edit_link($blockId, $pollId = null) {
    global $COURSE;

    $urlParams = [
        'blockid' => $blockId,
        'courseid' => $COURSE->id,
    ];

    if ($pollId) {
        $urlParams['id'] = $pollId;
    }

    $url = new moodle_url('/blocks/poll/view.php', $urlParams);
    return html_writer::link($url, get_string('edit', 'block_poll'));
}

function get_results_link($blockId, $pollId) {
    global $COURSE;

    $pageparam = [
        'blockid' => $blockId,
        'courseid' => $COURSE->id,
        'pollid' => $pollId,
    ];

    $pollResultsUrl = new moodle_url('/blocks/poll/results.php', $pageparam);
    return html_writer::link($pollResultsUrl, get_string('pollresults', 'block_poll'));
}

function get_answer_link($blockId, $pollId) {
    global $COURSE;

    $pageparam = [
        'blockid' => $blockId,
        'courseid' => $COURSE->id,
        'pollid' => $pollId,
    ];

    $answerUrl = new moodle_url('/blocks/poll/answer.php', $pageparam);
    return html_writer::link($answerUrl, get_string('answerpoll', 'block_poll'));
}

function get_footer_for_existent_poll($poll, $blockId) {
    global $USER;

    $userCanEditPoll = $poll->userid == $USER->id;
    if ($userCanEditPoll) {
        return get_footer_for_poll_editor($poll, $blockId);
    } else {
        return get_footer_for_poll_participant($blockId, $poll);
    }
}

function get_footer_for_poll_editor($poll, $blockId) {
    if (!poll_has_answers($poll->id)) {
        return get_edit_link($blockId, $poll->id);
    }

    return get_results_link($blockId, $poll->id);
}

function get_footer_for_poll_participant($blockId, $poll) {
    global $USER;

    if (has_answered_poll($poll->id, $USER->id)) {
        return get_results_link($blockId, $poll->id);
    }

    return get_answer_link($blockId, $poll->id);
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