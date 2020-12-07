<?php

class block_poll_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('title_field', 'block_poll'));
        $mform->setDefault('config_title', '');
        $mform->setType('config_title', PARAM_TEXT);

        $mform->addElement('text', 'config_text', get_string('text_field', 'block_poll'));
        $mform->setDefault('config_text', '');
        $mform->setType('config_text', PARAM_RAW);
    }
}