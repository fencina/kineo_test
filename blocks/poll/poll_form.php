<?php

require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot.'/blocks/poll/lib.php');

class poll_form extends moodleform {
    function definition() {
        $mform =& $this->_form;

        $creatingPoll = true;
        if ($creatingPoll) {
            $mform->addElement('header','displayinfo', get_string('formheader', 'block_poll'));

            $mform->addElement('text', 'title', get_string('title_field', 'block_poll'));
            $mform->setType('title', PARAM_RAW);
            $mform->addRule('title', null, 'required', null, 'client');

            $mform->addElement('text', 'question', get_string('question_field', 'block_poll'));
            $mform->setType('question', PARAM_RAW);
            $mform->addRule('question', null, 'required', null, 'client');

            // TODO add options dynamically. For now only 3 options are allowed. Check blocks/poll/lib.php
            $options = block_poll_options();
            foreach ($options as $i => $option) {
                $mform->addElement('text', 'option_'.$i, 'Option');
                $mform->setType('option_'.$i, PARAM_RAW);
            }
        }

        // TODO show answer form. Extract to another file.
        $answerPoll = false;
        if ($answerPoll) {
            $optionNames = block_poll_options();
            $options = [];

            foreach ($optionNames as $id => $option) {
                $options[] = $mform->createElement('radio', 'answer', '', $option, $id);
            }
            $mform->addGroup($options, 'radioar', '', [' '], false);
            // TODO remove default and add required rule


        }

        $mform->addElement('hidden', 'blockid');
        $mform->addElement('hidden', 'courseid');
        $mform->addElement('hidden','id','0');
        $this->add_action_buttons();
    }
}