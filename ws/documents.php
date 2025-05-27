<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 *
 * @package    mod_openchat
 * @copyright  2025 Niels Seidel <niels.seidel@fernuni-hagen.de>, CATALPA, FernUniversität Hagen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot . "/lib/moodlelib.php");
require_once($CFG->dirroot . '/group/lib.php');

/**
 * Class
 */
class mod_openchat_documents extends external_api {
    /**
     * Upload document.
     */
    public static function document_upload_parameters() {
        return new external_function_parameters([
            'cmid' => new external_value(PARAM_INT, 'Course module ID'),
            'file' => new external_value(PARAM_RAW, 'Base64-encoded file content'),
            'filename' => new external_value(PARAM_FILE, 'Filename'),
            'mimetype' => new external_value(PARAM_RAW, 'MIME type'),
        ]);
    }

    public static function document_upload($cmid, $base64file, $filename, $mimetype) {
        global $USER;

        $cm = get_coursemodule_from_id('openchat', $cmid, 0, false, MUST_EXIST);
        $context = context_module::instance($cm->id);
        self::validate_context($context);
        require_capability('mod/openchat:upload', $context);

        $decoded = base64_decode($base64file);
        if ($decoded === false) {
            throw new invalid_parameter_exception('Failed to decode file content.');
        }

        $fs = get_file_storage();

        // Save to file storage
        $fileinfo = [
            'contextid' => $context->id,
            'component' => 'mod_openchat',
            'filearea'  => 'attachment',
            'itemid'    => $cm->instance,
            'filepath'  => '/',
            'filename'  => $filename
        ];

        // Write file to temporary location
        $temp = tempnam(sys_get_temp_dir(), 'upload_');
        file_put_contents($temp, $decoded);

        $fs->create_file_from_pathname($fileinfo, $temp);
        unlink($temp);

        return ['success' => true];
    }

    public static function document_upload_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Upload success status')
        ]);
    }


    /**
     * Something.
     */
    public static function document_upload_is_allowed_from_ajax() {
        return true;
    }

    /**
     * List documents.
     */
    public static function list_documents_parameters() {
        return new external_function_parameters([
            'cmid' => new external_value(PARAM_INT, 'Course module ID'),
        ]);
    }

    /**
     * Something.
     */
    public static function list_documents($cmid) {
        global $DB;

        $cm = get_coursemodule_from_id('openchat', $cmid, 0, false, MUST_EXIST);
        $context = \context_module::instance($cm->id);
        self::validate_context($context);

        $fs = get_file_storage();
        $files = $fs->get_area_files(
            $context->id,
            'mod_openchat',
            'attachment',
            $cm->instance,
            'filename',
            false
        );

        $output = [];
        foreach ($files as $file) {
            $output[] = [
                'filename' => $file->get_filename(),
                'url' => moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    $file->get_itemid(),
                    $file->get_filepath(),
                    $file->get_filename()
                )->out(false),
            ];
        }

        return $output;
    }

    /**
     * Something.
     */
    public static function list_documents_returns() {
        return new external_multiple_structure(
            new external_single_structure([
                'filename' => new external_value(PARAM_FILE, 'Name of the file'),
                'url' => new external_value(PARAM_URL, 'Download URL'),
            ])
        );
    }

    /**
     * Something.
     */
    public static function list_documents_is_allowed_from_ajax() {
        return true;
    }

    /**
     * Delete document.
     */
    public static function delete_document_parameters() {
        return new external_function_parameters([
            'cmid' => new external_value(PARAM_INT, 'Course module ID'),
            'filename' => new external_value(PARAM_TEXT, 'Filename to delete'),
        ]);
    }

    /**
     * Something.
     */
    public static function delete_document($cmid, $filename) {
        global $USER;

        $cm = get_coursemodule_from_id('openchat', $cmid, 0, false, MUST_EXIST);
        $context = \context_module::instance($cm->id);
        self::validate_context($context);

        $fs = get_file_storage();
        $files = $fs->get_area_files(
            $context->id,
            'mod_openchat',
            'attachment',
            $cm->instance,
            'filename', // order by
            false
        );

        foreach ($files as $file) {
            if ($file->get_filename() === $filename) {
                $file->delete();
                return ['success' => true];
            }
        }

        return ['success' => false];
    }

    /**
     * Something.
     */
    public static function delete_document_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'True if file was deleted'),
        ]);
    }

    /**
     * Something.
     */
    public static function delete_document_is_allowed_from_ajax() {
        return true;
    }
}
