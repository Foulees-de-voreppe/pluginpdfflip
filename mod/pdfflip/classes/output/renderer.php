<?php
namespace mod_pdfflip\output;

use plugin_renderer_base;
use context;

class renderer extends plugin_renderer_base {
    public function render_pdfflip(\stdClass $pdfflip, context $context): string {
        global $CFG;
        $output = html_writer::div(format_text($pdfflip->intro, $pdfflip->introformat, ['context' => $context]), 'intro');
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'mod_pdfflip', 'content', 0, 'itemid, filepath, filename', false);
        if ($files) {
            $file = reset($files);
            $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
            $output .= html_writer::tag('div', '', ['id' => 'flipbook', 'data-pdf' => $url->out(false)]);
            $this->page->requires->js('/mod/pdfflip/js/pdfflip.js');
            $this->page->requires->css('/mod/pdfflip/style.css');
        } else {
            $output .= get_string('pdfnotfound', 'pdfflip');
        }
        return $output;
    }
}