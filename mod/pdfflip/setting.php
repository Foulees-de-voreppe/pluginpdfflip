<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('modsettingpdfflip', get_string('pluginname', 'pdfflip'));
    $ADMIN->add('modsettings', $settings);
}