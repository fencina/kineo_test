<?php

require_once('db/poll_repository.php');
require_once($CFG->dirroot.'/blocks/poll/lib.php');

/**
 * Block poll class definition.
 */
class block_poll extends block_base {
    public function init() {
        // Title will be overwritten in specialization function
        $this->title = '';
    }

    public function specialization() {
        $this->title = $this->config->title ?? get_string('default_title', 'block_poll');
        $this->text = $this->config->text ?? get_string('default_text', 'block_poll');
    }

    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = $this->text;
        global $COURSE, $PAGE, $USER;

        $canEditBlock = $PAGE->user_is_editing();
        $footer = '';

        $repository = new poll_repository();
        if ($poll = $repository->get_poll_by_block($this->instance->id)) {
            $canEditPoll = $poll->userid == $USER->id;
            if ($canEditPoll) {
                if (poll_has_answers($poll->id)) {
                    $pageparam = [
                        'blockid' => $this->instance->id,
                        'courseid' => $COURSE->id,
                        'pollid' => $poll->id,
                    ];

                    $pollResultsUrl = new moodle_url('/blocks/poll/results.php', $pageparam);
                    $footer = html_writer::link($pollResultsUrl, get_string('pollresults', 'block_poll'));
                } else {
                    $url = new moodle_url('/blocks/poll/view.php', ['blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'id' => $poll->id]);
                    $footer = html_writer::link($url, get_string('edit', 'block_poll'));
                }
            } else {
                if (has_answered_poll($poll->id, $USER->id)) {
                    $pageparam = [
                        'blockid' => $this->instance->id,
                        'courseid' => $COURSE->id,
                        'pollid' => $poll->id,
                    ];

                    $pollResultsUrl = new moodle_url('/blocks/poll/results.php', $pageparam);
                    $footer = html_writer::link($pollResultsUrl, get_string('pollresults', 'block_poll'));
                } else {
                    $pageparam = [
                        'blockid' => $this->instance->id,
                        'courseid' => $COURSE->id,
                        'pollid' => $poll->id,
                    ];

                    $answerUrl = new moodle_url('/blocks/poll/answer.php', $pageparam);
                    $footer = html_writer::link($answerUrl, get_string('answerpoll', 'block_poll'));
                }
            }
        } else {
            if ($canEditBlock) {
                $url = new moodle_url('/blocks/poll/view.php', ['blockid' => $this->instance->id, 'courseid' => $COURSE->id]);
                $footer = html_writer::link($url, get_string('edit', 'block_poll'));
            }
        }

        $this->content->footer = $footer;
        return $this->content;
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function applicable_formats()
    {
        return [
            'site-index' => true,
            'my' => true,
        ];
    }
}