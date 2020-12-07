<?php

require_once('../../config.php');
require_once('poll_answer_form.php');
require_once('db/poll_repository.php');
require_once($CFG->dirroot.'/blocks/poll/lib.php');

global $DB, $OUTPUT, $PAGE, $USER;

$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$pollid = required_param('pollid', PARAM_INT);

if (!$course = $DB->get_record('course', ['id' => $courseid])) {
    print_error('invalidcourse', 'block_poll', $courseid);
}

require_login($course);

$repository = new poll_repository();
$poll = $repository->get_poll_by_id($pollid);

$PAGE->set_heading($poll->title);

$settingsnode = $PAGE->settingsnav->add(get_string('pluginname', 'block_poll'));
$editurl = new moodle_url('/blocks/poll/answer.php', ['courseid' => $courseid, 'blockid' => $blockid, 'pollid' => $pollid]);
$editnode = $settingsnode->add(get_string('answerpoll', 'block_poll'), $editurl);
$editnode->make_active();

$answerForm = new poll_answer_form($poll);

$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;
$toform['pollid'] = $pollid;
$answerForm->set_data($toform);

if($answerForm->is_cancelled()) {
    // Cancelled forms redirect to the home page. TODO redirect to previous url
    redirect(new moodle_url('/?redirect=0'));
} else if ($fromform = $answerForm->get_data()) {
    $fromform->userid = $USER->id;
    answer_poll($fromform);

    $courseurl = new moodle_url('/course/view.php', ['id' => $courseid]);
    redirect($courseurl);
} else {
    echo $OUTPUT->header();
    $poll = $repository->get_poll_by_id($pollid);

    if (has_answered_poll($pollid, $USER->id)) {
        redirect(new moodle_url('/?redirect=0'));
    }

    // Answering
    foreach ($poll->options as $option) {
        $poll->{$option->tag} = $option->description;
    }
    $answerForm->set_data($poll);
    $answerForm->display();

    echo $OUTPUT->footer();
}