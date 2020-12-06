<?php

require_once('../../config.php');
require_once('poll_form.php');
require_once('db/poll_repository.php');

global $DB, $OUTPUT, $PAGE;

$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);

$id = optional_param('id', 0, PARAM_INT);
$viewpage = optional_param('viewpage', false, PARAM_BOOL);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_poll', $courseid);
}

require_login($course);

$PAGE->set_url('/blocks/poll/view.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('edithtml', 'block_poll'));

$settingsnode = $PAGE->settingsnav->add(get_string('pluginname', 'block_poll'));
$editurl = new moodle_url('/blocks/poll/view.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
// TODO Set poll title as navigation text
$editnode = $settingsnode->add(get_string('editpage', 'block_poll'), $editurl);
$editnode->make_active();

$poll = new poll_form();
$repository = new poll_repository();

$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;
$toform['id'] = $id;
$poll->set_data($toform);

if($poll->is_cancelled()) {
    // Cancelled forms redirect to the home page. TODO redirect to previous url
    $courseurl = new moodle_url('/', array('id' => $id));
    redirect($courseurl);
} else if ($fromform = $poll->get_data()) {
    if ($fromform->id != 0) {
        // TODO store user answer. Validate if has previous answer for user
        if (!$repository->update_poll($fromform)) {
            print_error('updateerror', 'block_simplehtml');
        }
    } else {
        create_poll($fromform);
    }

    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
    redirect($courseurl);
} else {
    $site = get_site();
    echo $OUTPUT->header();
    if ($id) {
        $pollpage = $repository->get_poll_by_id($id);
        if($viewpage) {
            block_poll_print_page($pollpage);
        } else {
            // Editing poll
            $poll->set_data($pollpage);
            $poll->display();
        }
    } else {
        $poll->display();
    }
    echo $OUTPUT->footer();
}