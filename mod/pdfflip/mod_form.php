<?php
require_once(__DIR__.'/../../lib/formslib.php');

class mod_pdfflip_mod_form extends moodleform_mod {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'name', get_string('name'), array('size' => '64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $this->standard_intro_elements();

        $mform->addElement('filepicker', 'pdf', get_string('pdf', 'pdfflip'), null, array('accepted_types' => 'pdf'));
        $mform->addRule('pdf', null, 'required', null, 'client');

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }
}