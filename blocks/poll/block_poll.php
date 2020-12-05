<?php

/**
 * Block poll class definition.
 */
class block_poll extends block_base {
    public function init() {
        $this->title = get_string('poll', 'block_poll');
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
        $this->content->footer = 'Footer here...';

        return $this->content;
    }

    public function instance_allow_multiple() {
        return true;
    }
}