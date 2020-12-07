<?php

require_once("{$CFG->libdir}/formslib.php");

class poll_results_form extends moodleform {
    /**
     * @var object
     */
    protected $poll;

    public function __construct(object $poll)
    {
        $this->poll = $poll;
        parent::__construct();
    }

    function definition() {
        $mform =& $this->_form;

        $mform->addElement('header','question', $this->poll->question);

        foreach ($this->poll->options as $option) {
            $mform->addElement('text', $option->tag, $option->description, ['disabled']);
            $mform->setType($option->tag, PARAM_RAW);
        }

        $mform->addElement('hidden', 'blockid');
        $mform->addElement('hidden', 'courseid');
        $mform->addElement('hidden','pollid','0');
    }
}