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

namespace mod_openchat\permission;


/**
 * Something.
 */
class context {

    /**
     * Something.
     * @var bla.
     */
    protected $_context;

    /**
     * Something.
     * @var bla.
     */
    protected $_userid;

    /**
     * Something.
     * @var bla.
     */
    protected $_roles;

    /**
     * Something.
     */
    public function __construct($userid, $context) {
        require_login();
        $this->_context = $context;
        $this->_userid = $userid;
        $this->_roles = get_user_roles($this->_context, $this->_userid);
    }

    /**
     * Something.
     */
    public function issiteadmin() {
        return is_siteadmin($this->_userid);
    }

    /**
     * Something.
     */
    public function ismanager() {
        return $this->findRole('manager');
    }

    /**
     * Something.
     */
    public function iscoursecreator() {
        return $this->findRole('coursecreator');
    }

    /**
     * Something.
     */
    public function iseditingteacher() {
        return $this->findRole('editingteacher');
    }

    /**
     * Something.
     */
    public function isteacher() {
        return $this->findRole('teacher');
    }

    /**
     * Something.
     */
    public function isstudent() {
        return $this->findRole('student');
    }

    /**
     * Something.
     */
    public function isguest() {
        return is_guest($this->_context, $this->_userid);
    }

    /**
     * Something.
     */
    public function isuser() {
        return $this->findRole('user');
    }

    /**
     * Something.
     */
    public function isfrontpage() {
        return $this->findRole('frontpage');
    }

    /**
     * Something.
     */
    public function findrole($shortname) {
        foreach ($this->_roles as $role) {
            if (isset($role->shortname) && strtolower($role->shortname) === strtolower($shortname)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Something.
     */
    public function hasviewcapability() {
        return is_viewing($this->_context, $this->_userid);
    }

    /**
     * Something.
     */
    public function isenrolled() {
        return is_enrolled($this->_context, $this->_userid);
    }

    /**
     * Something.
     */
    public function isanykindofmoderator() {
        if (
            $this->isSiteAdmin() ||
            $this->isManager() ||
            $this->isCourseCreator() ||
            $this->isEditingTeacher() ||
            $this->isTeacher()
        ) {
            return true;
        }
        return false;
    }

    /**
     * Something.
     */
    public function getcontext() {
        return $this->_context;
    }

    /**
     * Something.
     */
    public function getcoursecontext() {
        $context = $this->_context;
        return $context->get_course_context();
    }
}


