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
 * Quiz question tracker support class
 *
 * @package     block_qtracker
 * @author      André Storhaug <andr3.storhaug@gmail.com>
 * @copyright   2021 NTNU
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_qtracker;

defined('MOODLE_INTERNAL') || die();

/**
 * Quiz question tracker support class
 *
 * @author      André Storhaug <andr3.storhaug@gmail.com>
 * @copyright   2021 NTNU
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quiz_qtracker extends base_qtracker {

    protected $attemptobj = null;
    protected $quba = null;
    protected $slots = null;

    public function __construct() {
        $this->attemptid = optional_param('attempt', null, PARAM_INT);
        $this->page = optional_param('page', 0, PARAM_INT); // TODO: change to 'all' - gives all slots.
        $this->cmid = optional_param('cmid', null, PARAM_INT);
    }

    private function get_attemptobj() {
        if (is_null($this->attemptobj)) {
            $this->attemptobj = quiz_create_attempt_handling_errors($this->attemptid, $this->cmid);
        }
        return $this->attemptobj;
    }

    public function get_quba() {
        if (is_null($this->quba)) {
            $attemptobj = $this->get_attemptobj();
            if (!is_null($attemptobj)) {
                $this->quba = \question_engine::load_questions_usage_by_activity($attemptobj->get_attempt()->uniqueid);
            }
        }
        return $this->quba;
    }

    public function get_slots() {
        $this->slots = $this->get_attemptobj()->get_slots($this->page);
        return $this->slots;
    }

    public function get_contextid() {
        global $PAGE;
        $context = $PAGE->context;
        return $context->id;
    }

    public function can_view_issues() {
        global $PAGE;
        $context = $PAGE->context;
        return has_capability('mod/quiz:viewreports', $context);
    }

    public function is_active_attempt() {
        return isset($this->attemptid);
    }
}
