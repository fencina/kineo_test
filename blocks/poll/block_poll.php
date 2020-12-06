<?php

require_once('db/poll_repository.php');

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
                $url = new moodle_url('/blocks/poll/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'id' => $poll->id));

                // TODO si sos el creador y la encuesta no tiene ninguna respuesta, se puede modificar
                // si sos creador pero la encuesta ya tiene respuesta, solo podés ver las respuestas
                // si no sos el creador y no respondiste la encuesta ves el link para responderla
                // si no sos el creador y respondiste a la encuesta, solo podés ver las respuestas
                $footer = html_writer::link($url, get_string('edit', 'block_poll'));
            } else {

                $pageparam = [
                    'blockid' => $this->instance->id,
                    'courseid' => $COURSE->id,
                    'id' => $poll->id,
                ];

                $editurl = new moodle_url('/blocks/poll/view.php', $pageparam);
                $footer = html_writer::link($editurl, 'Answer');
            }
        } else {
            if ($canEditBlock) {
                $url = new moodle_url('/blocks/poll/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
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