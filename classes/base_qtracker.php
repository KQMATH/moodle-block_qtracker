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
 * Base class for qtracker types.
 *
 * @package     block_qtracker
 * @author      André Storhaug <andr3.storhaug@gmail.com>
 * @copyright   2021 NTNU
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_qtracker;

defined('MOODLE_INTERNAL') || die();

/**
 * Base class for qtracker types.
 *
 * @author      André Storhaug <andr3.storhaug@gmail.com>
 * @copyright   2021 NTNU
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class base_qtracker {

    /**
     * Returns the question_usage_by_activity.
     * @return \question_usage_by_activity
     */
    abstract function get_quba();

    /**
     * Returns the slots.
     * @return array
     */
    abstract function get_slots();

    /**
     * Returns the context id.
     * @return int
     */
    abstract function get_contextid();

    /**
     * Checks if the current user can view submitted issues.
     * @return bool
     */
    abstract function can_view_issues();

    /**
     * Checks if the current page represents an active question attempt.
     * @return bool
     */
    abstract function is_active_attempt();
}
