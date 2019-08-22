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
 * Block to show a pupil's own achievement point totals from The Hub.
 *
 * @package    block_mytimetable
 * @copyright  2018 Test Valley School
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_mytimetable extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_mytimetable');
    }

    public function applicable_formats() {
        return array('all' => true, 'tag' => false);
    }

    public function specialization() {
        $this->title = isset($this->config->title) ? $this->config->title : get_string('newmytimetableblock', 'block_mytimetable');
    }

    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Allow the use of the global settings for this block.
     */
    public function has_config() {
	return true;
    }

    /**
     * Determine whether or not the block is correctly configured for API access.
     *
     * @return bool
     */
    protected function is_configured() {
	global $CFG;

	return true;
    }

    public function get_content() {
        global $CFG, $USER, $DB;

        if ($this->content !== NULL) {
            return $this->content;
        }	

	// initialise block content object
        $this->content = new stdClass();
	$this->content->text = '';
	$this->content->footer = '';

	if (empty($this->instance)) {
		return $this->content;
	}

	if (!$USER->id) {
		//$this->content->text .= html_writer::tag( 'p', get_string('notloggedin', 'block_myachievementpoints') );
		// empty content if not logged in -- block will not appear
		$this->content->text = '';
		$this->content->footer = '';
		return $this->content;
	}

	// are the block's global config options configured correctly? if not, fail early
	if (!$this->is_configured()) {
		$this->content->text .= html_writer::tag( 'p', get_string('notconfigured', 'block_mytimetable') );
		$this->content->footer = '';
		return $this->content;
	}

	// data for template
	$data = new \stdClass();

	// normal rendering if we have data
	$renderer = $this->page->get_renderer('block_mytimetable');
	$block = new \block_mytimetable\output\block($data);

	$this->content->text .= $renderer->render($block);

        return $this->content;
    }

    /**
     * Returns true if the block can be docked.
     * The mentees block can only be docked if it has a non-empty title.
     * @return bool
     */
    public function instance_can_be_docked() {
        return parent::instance_can_be_docked() && isset($this->config->title) && !empty($this->config->title);
    }

    /**
     * Get JavaScript required for this block.
     */
    public function get_required_javascript() {
	    parent::get_required_javascript();
	    $arguments = [ 'instanceid' => $this->instance->id ];

	    $this->page->requires->js_call_amd('block_mytimetable/mytimetable_modal', 'init');
    }
}

