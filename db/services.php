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

$functions = [

    'mod_openchat_load_settings' => [
        'classname'   => 'mod_openchat_settings',
        'methodname'  => 'loadSettings',
        'classpath'   => 'mod/openchat/ws/settings.php',
        'description' => 'Get plugin settings.',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true,
    ],
    'mod_openchat_update_settings' => [
        'classname'   => 'mod_openchat_settings',
        'methodname'  => 'updateSettings',
        'classpath'   => 'mod/openchat/ws/settings.php',
        'description' => 'Update plugin settings.',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true,
    ],

    'mod_openchat_preference' => [
        'classname'   => 'mod_openchat_preference',
        'methodname'  => 'preference',
        'classpath'   => 'mod/openchat/ws/preference.php',
        'description' => 'Get plugin preference.',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true,
    ],

    'mod_openchat_logging' => [
        'classname'   => 'mod_openchat_external',
        'methodname'  => 'logging',
        'classpath'   => 'mod/openchat/ws/logging.php',
        'description' => 'Get store log events.',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true,
    ],
    'mod_openchat_triggerEvent' => [
        'classname'   => 'mod_openchat_event',
        'methodname'  => 'triggerEvent',
        'classpath'   => 'mod/openchat/ws/event.php',
        'description' => 'Trigger an event',
        'type'        => 'read',
        'ajax'        => 'write',
        'loginrequired' => true,
    ],
    'mod_openchat_document_upload' => [
        'classname'   => 'mod_openchat_documents',
        'methodname'  => 'upload_document',
        'classpath'   => 'mod/openchat/ws/documents.php',
        'description' => 'Upload a document to be used for RAG',
        'type'        => 'write',
        'ajax'        => true,
        'capabilities' => 'mod/openchat:upload',
    ],
    'mod_openchat_document_list' => [
        'classname'   => 'mod_openchat_documents',
        'methodname'  => 'list_documents',
        'classpath'   => 'mod/openchat/ws/documents.php',
        'description' => 'List uploaded documents available for RAG',
        'type'        => 'write',
        'ajax'        => true,
        'capabilities' => 'mod/openchat:upload',
    ],
    'mod_openchat_document_delete' => [
        'classname'   => 'mod_openchat_documents',
        'methodname'  => 'delete_document',
        'classpath'   => 'mod/openchat/ws/documents.php',
        'description' => 'Delete a document that should notbe available for RAG anymore',
        'type'        => 'write',
        'ajax'        => true,
        'capabilities' => 'mod/openchat:upload',
    ],
];
