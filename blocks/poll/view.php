<?php

require_once('../../config.php');
require_once('poll_form.php');
require_once('db/poll_repository.php');
require_once($CFG->dirroot.'/blocks/poll/lib.php');

global $DB, $OUTPUT, $PAGE;

$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);

if (!$course = $DB->get_record('course', ['id' => $courseid])) {
    print_error('invalidcourse', 'block_poll', $courseid);
}

require_login($course);

$PAGE->set_heading(get_string('edithtml', 'block_poll'));

$settingsnode = $PAGE->settingsnav->add(get_string('pluginname', 'block_poll'));
$editurl = new moodle_url('/blocks/poll/view.php', ['id' => $id, 'courseid' => $courseid, 'blockid' => $blockid]);
$editnode = $settingsnode->add(get_string('editpage', 'block_poll'), $editurl);
$editnode->make_active();

$pollForm = new poll_form();

$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;
$toform['id'] = $id;
$pollForm->set_data($toform);

if($pollForm->is_cancelled()) {
    // Cancelled forms redirect to the home page. TODO redirect to previous url
    redirect(new moodle_url('/?redirect=0'));
} else if ($fromform = $pollForm->get_data()) {
    if ($fromform->id) {
        update_poll($fromform);
    } else {
        create_poll($fromform);
    }

    redirect(new moodle_url('/?redirect=0'));
} else {
    echo $OUTPUT->header();
    if ($id) {
        $repository = new poll_repository();
        $poll = $repository->get_poll_by_id($id);

        if (poll_has_answers($poll->id)) {
            $courseurl = new moodle_url('/?redirect=0');
            redirect($courseurl);
        }

        // Editing poll
        foreach ($poll->options as $i => $option) {
            $poll->{$option->tag} = $option->description;
        }
        $pollForm->set_data($poll);
        $pollForm->display();
    } else {
        $pollForm->display();
    }
    echo $OUTPUT->footer();
}