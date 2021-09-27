<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Capquiz question tracker support class
 *
 * @package     block_qtracker
 * @author      André Storhaug <andr3.storhaug@gmail.com>
 * @copyright   2021 NTNU
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_qtracker;

use \mod_capquiz\capquiz;

defined('MOODLE_INTERNAL') || die();

/**
 * Capquiz question tracker support class
 *
 * @author      André Storhaug <andr3.storhaug@gmail.com>
 * @copyright   2021 NTNU
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class capquiz_qtracker extends base_qtracker {

    protected $capquiz = null;

    public function __construct() {
        $cmid = optional_param('id', null, PARAM_INT);
        if (!is_null($cmid)) {
            $this->capquiz = new capquiz($cmid);
        }
    }

    public function get_quba() {
        $user = $this->capquiz->user();
        $quba = $user->question_usage();
        return $quba;
    }

    public function get_slots() {
        $user = $this->capquiz->user();
        $qengine = $this->capquiz->question_engine($user);
        $attempt = $qengine->attempt_for_current_user();
        $slot = $attempt->question_slot();
        return [$slot];
    }

    public function get_contextid() {
        global $PAGE;
        $context = $PAGE->context;
        return $context->id;
    }

    public function can_view_issues() {
        return has_capability('mod/capquiz:instructor', $this->capquiz->context());
    }

    public function is_active_attempt() {
        return !has_capability('mod/capquiz:instructor', $this->capquiz->context());
    }
}
