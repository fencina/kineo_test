<?php

require_once("{$CFG->libdir}/formslib.php");

class poll_answer_form extends moodleform {
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
            $options[] = $mform->createElement('radio', 'polloptionid', '', $option->description, $option->id);
        }
        $mform->addGroup($options, 'radioar', '', [' '], false);
        // TODO remove default and add required rule
        $mform->setDefault('polloptionid', current($this->poll->options)->id);


        $mform->addElement('hidden', 'blockid');
        $mform->addElement('hidden', 'courseid');
        $mform->addElement('hidden','pollid','0');
        $this->add_action_buttons();
    }
}