<?php

require_once('constants.php');

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
        global $COURSE, $DB, $PAGE;

        $canmanage = $PAGE->user_is_editing($this->instance->id);

        if ($pollpages = $DB->get_records(TABLE_POLLS, array('blockid' => $this->instance->id))) {
            $this->content->text .= html_writer::start_tag('ul');
            foreach ($pollpages as $pollpage) {
                if ($canmanage) {
                    $pageparam = array('blockid' => $this->instance->id,
                        'courseid' => $COURSE->id,
                        'id' => $pollpage->id);
                    $editurl = new moodle_url('/blocks/poll/view.php', $pageparam);
                    $editpicurl = new moodle_url('/pix/f/edit.gif');
                    $edit = html_writer::link($editurl, 'Answer');
                } else {
                    $edit = '';
                }
                $pageurl = new moodle_url('/blocks/poll/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'id' => $pollpage->id, 'viewpage' => true));
                $this->content->text .= html_writer::start_tag('li');
                $this->content->text .= html_writer::link($pageurl, $pollpage->pagetitle);
                $this->content->text .= $edit;
                $this->content->text .= html_writer::end_tag('li');
            }
            $this->content->text .= html_writer::end_tag('ul');
        }

        $url = new moodle_url('/blocks/poll/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));

        // TODO si sos el creador y la encuesta no tiene ninguna respuesta, se puede modificar
        // si sos creador pero la encuesta ya tiene respuesta, solo podés ver las respuestas
        // si no sos el creador y no respondiste la encuesta ves el link para responderla
        // si no sos el creador y respondiste a la encuesta, solo podés ver las respuestas
        $this->content->footer = html_writer::link($url, get_string('edit', 'block_poll'));

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