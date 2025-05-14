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


namespace mod_openchat\core;

/**
 * Something.
 */
class pinfo {

    /**
     * Something.
     */
    public static function getdata() {
        $d = new \stdClass();
        $d->basedir = strtolower(dirname(dirname(__DIR__)));
        $d->name = basename($d->basedir);
        $d->type = basename(dirname($d->basedir));
        $d->fullName = "{$d->type}_{$d->name}";
        $d->moodlePath = "/{$d->type}/{$d->name}";
        return $d;
    }

}
