<?php

function pdfflip_supports(string $feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        default:
            return null;
    }
}

function pdfflip_add_instance(stdClass $data) {
    global $DB;
    $data->timecreated = time();
    $data->timemodified = $data->timecreated;
    $data->id = $DB->insert_record('pdfflip', $data);
    $context = context_module::instance($data->coursemodule);
    file_save_draft_area_files($data->pdf, $context->id, 'mod_pdfflip', 'content', 0);
    return $data->id;
}

function pdfflip_update_instance(stdClass $data) {
    global $DB;
    $data->timemodified = time();
    $data->id = $data->instance;
    $DB->update_record('pdfflip', $data);
    $context = context_module::instance($data->coursemodule);
    file_save_draft_area_files($data->pdf, $context->id, 'mod_pdfflip', 'content', 0);
    return true;
}

function pdfflip_delete_instance($id) {
    global $DB;
    if (!$pdfflip = $DB->get_record('pdfflip', ['id' => $id])) {
        return false;
    }
    $DB->delete_records('pdfflip', ['id' => $pdfflip->id]);
    return true;
}

function mod_pdfflip_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }
    require_login($course, true, $cm);
    if ($filearea !== 'content') {
        return false;
    }
    array_shift($args); // ignore itemid
    $filename = array_pop($args);
    $filepath = $args ? ('/' . implode('/', $args) . '/') : '/';
    $fs = get_file_storage();
    if (!($file = $fs->get_file($context->id, 'mod_pdfflip', 'content', 0, $filepath, $filename))) {
        return false;
    }
    send_stored_file($file, 0, 0, $forcedownload, $options);
}