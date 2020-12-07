<?php

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
        $this->content->footer = get_block_content_footer($this->instance->id);
        
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