<?php

require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot.'/blocks/poll/lib.php');

class poll_form extends moodleform {
    function definition() {
        $mform =& $this->_form;
        // TODO show poll question
        $mform->addElement('header','displayinfo', get_string('textfields', 'block_poll'));

        $optionNames = block_poll_options();
        $options = [];

        foreach ($optionNames as $id => $option) {
            $options[] = $mform->createElement('radio', 'answer', '', $option, $id);
        }
        $mform->addGroup($options, 'radioar', '', [' '], false);
        // TODO remove default and add required rule

        $mform->addElement('hidden', 'blockid');
        $mform->addElement('hidden', 'courseid');
        $mform->addElement('hidden','id','0');

        $this->add_action_buttons();
    }
}