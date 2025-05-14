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

namespace mod_openchat\privacy;


use core_privacy\local\request\userlist;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\deletion_criteria;
use core_privacy\local\request\writer;
use core_privacy\local\request\helper as request_helper;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\transform;
use tool_dataprivacy\context_instance;

/**
 * Privacy provider implementation for mod_openchat.
 * @package mod_openchat
 */
class provider implements
    // This plugin has data.
    \core_privacy\local\metadata\provider,

    // This plugin currently implements the original plugin\provider interface.
    \core_privacy\local\request\plugin\provider,

    // This plugin is capable of determining which users have data within it.
    \core_privacy\local\request\core_userlist_provider,

    // This plugin has some sitewide user preferences to export.
    \core_privacy\local\request\user_preference_provider {



    use subcontext_info;

    /**
     * Returns metadata about this plugin.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_external_location_link('llm_request', [
            'chatrequest' => 'privacy:metadata:llm_request:chatrequest',
        ], 'privacy:metadata:llm_request');

        $collection->add_database_table(
            'logstore_standard_log',
            [
                'userid' => 'privacy:metadata:logstore_standard_log:userid',
                'eventname' => 'privacy:metadata:logstore_standard_log:eventname',
                'action' => 'privacy:metadata:logstore_standard_log:action',
                'other' => 'privacy:metadata:logstore_standard_log:other',
            ],
            'privacy:metadata:logstore_standard_log'
        );

        $collection->add_user_preference(
            'accepted-informed-consent',
            'privacy:metadata:preference:accepted-informed-consent'
        );

        return $collection;
    }

    /**
     * Returns contexts containing user information.
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();
        $params = [
            'modname'       => 'forum',
            'contextlevel'  => CONTEXT_MODULE,
            'userid'        => $userid,
        ];

        // Discussion creators.
        $sql = "SELECT c.id
                  FROM {context} c
                  JOIN {course_modules} cm ON cm.id = c.instanceid AND c.contextlevel = :contextlevel
                  JOIN {modules} m ON m.id = cm.module AND m.name = :modname
                  JOIN {openpage} f ON f.id = cm.instance
                  JOIN {logstore_standard_log} d ON d.contextinstanceid = f.id
                 WHERE d.userid = :userid
        ";
        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Export user data for the approved contexts.
     */
    public static function export_user_data(approved_contextlist $contextlist): void {
        // Implement export logic if you store user data.
    }

    /**
     * Something.
     */
    public static function export_user_preferences(int $userid) {
        $preference = get_user_preference('accepted-informed-consent', null, $userid);
        if (null !== $preference) {
            switch ($preference) {
                case 0:
                    $preferencedescription = get_string('accepted-informed-consent', 'mod_openchat');
                    break;
                case 1:
                default:
                    $preferencedescription = get_string('withdraw-informed-consent', 'mod_openchat');
                    break;
            }
            writer::export_user_preference('mod_openchat', 'accepted-informed-consent', $preference, $preferencedescription);
        }
    }

    /**
     * Delete all user data in the context.
     */
    public static function delete_data_for_all_users_in_context(\context $context): void {
        // Implement deletion logic if you store user data.
    }

    /**
     * Delete data for a specific user.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist): void {
        // Implement deletion logic for this specific user.
    }

    /**
     * Get users associated with a context.
     */
    public static function get_users_in_context(userlist $userlist): void {
        $context = $userlist->get_context();

        if (!is_a($context, \context_module::class)) {
            return;
        }

        $params = [
            'instanceid'    => $context->instanceid,
            'modulename'    => 'openchat',
        ];

        // Discussion authors.
        $sql = "SELECT d.userid
                  FROM {course_modules} cm
                  JOIN {modules} m ON m.id = cm.module AND m.name = :modulename
                  JOIN {openchat} f ON f.id = cm.instance
                 WHERE cm.id = :instanceid";
        $userlist->add_from_sql('userid', $sql, $params);
    }

    /**
     * Delete data for users in approved context.
     */
    public static function delete_data_for_users(approved_userlist $userlist): void {
        // Bulk delete user data if applicable.
    }
}
