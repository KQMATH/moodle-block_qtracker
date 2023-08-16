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
 * Question Tracker block definition
 * Provides block for registering question issues for activity modules
 *
 * @package     block_qtracker
 * @author      André Storhaug <andr3.storhaug@gmail.com>
 * @copyright   2021 NTNU
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use \local_qtracker\output\issue_registration_block;
use \block_qtracker\quiz_qtracker;
use \block_qtracker\capquiz_qtracker;

defined('MOODLE_INTERNAL') || die();

/**
 * Block for registering question issues for activity modules
 *
 * @author      André Storhaug <andr3.storhaug@gmail.com>
 * @copyright   2021 NTNU
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_qtracker extends \block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_qtracker');
    }

    public function applicable_formats() {
        return array('all' => false, 'mod-quiz' => true, 'mod-capquiz' => true);
    }

    public function has_config() {
        return false;
    }

    public function get_content() {
        global $COURSE;

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->text = '';
        $this->content->footer = '';

        $qtrackertype = $this->get_qtracker_type();
        if (is_null($qtrackertype)) {
            $this->content->text = get_string('not_supported_qtracker_type', 'block_qtracker');
            return $this->content;
        }

        $activeattempt = $qtrackertype->is_active_attempt();
        if (!$activeattempt) {
            $canviewissues = $qtrackertype->can_view_issues();
            if ($canviewissues) {
                $url = new moodle_url('/local/qtracker/view.php', array('courseid' => $COURSE->id));
                $this->content->text .= html_writer::link($url, get_string('view_issues', 'block_qtracker'));
            }
            return $this->content;
        }

        $currentcontext = $this->page->context->get_course_context(false);
        if (!empty($this->config->text)) {
            $this->content->text = $this->config->text;
        }

        if (empty($currentcontext)) {
            return $this->content;
        }

        if (isset($this->config->text)) {
            $this->content->text = $this->config->text;
        } else {
            $this->content->text = html_writer::tag('p', get_string('question_problem_details', 'block_qtracker'));
        }

        $quba = $qtrackertype->get_quba();
        $slots = $qtrackertype->get_slots();
        $contextid = $qtrackertype->get_contextid();

        $templatable = new issue_registration_block($quba, $slots, $contextid);
        $renderer = $this->page->get_renderer('local_qtracker');
        $this->content->text .= $renderer->render($templatable);

        return $this->content;
    }

    /**
     * Get the supported activity module type
     * @return base_qtracker
     */
    private function get_qtracker_type() {
        $modname = $this->page->cm->modname;
        switch ($modname) {
            case 'quiz':
                return new quiz_qtracker();
            case 'capquiz':
                return new capquiz_qtracker();
            default:
                return null;
        }
    }
}
