<?php
require_once(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID
$pdfflipid = optional_param('n', 0, PARAM_INT); // pdfflip instance ID

if ($id) {
    $cm = get_coursemodule_from_id('pdfflip', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $pdfflip = $DB->get_record('pdfflip', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($pdfflipid) {
    $pdfflip = $DB->get_record('pdfflip', array('id' => $pdfflipid), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $pdfflip->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('pdfflip', $pdfflip->id, $course->id, false, MUST_EXIST);
} else {
    throw new moodle_exception('missingidandcmid');
}

require_course_login($course, true, $cm);

$context = context_module::instance($cm->id);

$PAGE->set_url('/mod/pdfflip/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($pdfflip->name));
$PAGE->set_heading(format_string($course->fullname));

$renderer = $PAGE->get_renderer('mod_pdfflip');

echo $OUTPUT->header();

echo $renderer->render_pdfflip($pdfflip, $context);

echo $OUTPUT->footer();