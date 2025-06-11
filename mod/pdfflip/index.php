<?php
require_once(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

$id = required_param('id', PARAM_INT); // Course ID

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
require_course_login($course);

$PAGE->set_url('/mod/pdfflip/index.php', array('id' => $id));
$PAGE->set_title(format_string($course->fullname));
$PAGE->set_heading(format_string($course->fullname));

echo $OUTPUT->header();

if (! $pdfflips = get_all_instances_in_course('pdfflip', $course)) {
    echo $OUTPUT->notification(get_string('nopdfflips', 'pdfflip'));
    echo $OUTPUT->footer();
    exit;
}

$table = new html_table();
$table->head  = array(get_string('name'), get_string('pdf', 'pdfflip'));

foreach ($pdfflips as $pdfflip) {
    $link = html_writer::link(new moodle_url('/mod/pdfflip/view.php', array('id' => $pdfflip->coursemodule)), format_string($pdfflip->name));
    $table->data[] = array($link, get_string('pdfavailable', 'pdfflip'));
}

echo html_writer::table($table);

echo $OUTPUT->footer();