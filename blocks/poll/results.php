<?php

require_once('../../config.php');
require_once('poll_results_form.php');
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
$editurl = new moodle_url('/blocks/poll/results.php', ['courseid' => $courseid, 'blockid' => $blockid, 'pollid' => $pollid]);
$editnode = $settingsnode->add(get_string('pollresults', 'block_poll'), $editurl);
$editnode->make_active();

$answerForm = new poll_results_form($poll);

$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;
$toform['pollid'] = $pollid;
$answerForm->set_data($toform);

echo $OUTPUT->header();
$poll = $repository->get_poll_by_id($pollid);

if ($poll->userid != $USER->id && !has_answered_poll($pollid, $USER->id)) {
    $courseurl = new moodle_url('/?redirect=0');
    redirect($courseurl);
}

$pollResults = get_poll_results($pollid);
foreach ($pollResults as $pollResult) {
    $poll->{$pollResult->tag} = $pollResult->answers_count . ' ' . get_string('votes', 'block_poll');
}
$answerForm->set_data($poll);
$answerForm->display();

echo $OUTPUT->footer();