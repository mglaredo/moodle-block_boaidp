<?php
/*
 * +----------------------------------------------------------------------+
 * | PHP Version 5                                                        |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2013 Miguel Gonzalez Laredo                            |
 * |                    mglaredo@ugr.es                                   |
 * |                    University of Granada                             |
 * |                                                                      |
 * | boaidp -- A Moodle Block for generating and editing Course's Metadata|
 * |           and the further connection with OAI v2.0's Data Providers  |
 * |                                                                      |
 * | This is free software; you can redistribute it and/or modify it under|
 * | the terms of the GNU General Public License as published by the      |
 * | Free Software Foundation; either version 2 of the License, or (at    |
 * | your option) any later version.                                      |
 * | This software is distributed in the hope that it will be useful, but |
 * | WITHOUT  ANY WARRANTY; without even the implied warranty of          |
 * | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the         |
 * | GNU General Public License for more details.                         |     
 * |                                                                      |
 * | You should have received a copy of the GNU General Public License    |
 * | along with software.                                                 |
 * | If not, see http://opensource.org/licenses/gpl-3.0.html.             |
 * |                                                                      |
 * +----------------------------------------------------------------------+
 * @copyright Copyright (c) 2013 Miguel Gonzalez Laredo. Virtual Learning Center CEVUG, University of Granada
 * @license    http://opensource.org/licenses/gpl-3.0.html     GNU Public License
 * @author Miguel Gonzalez Laredo, mglaredo@ugr.es                     
 */

defined('MOODLE_INTERNAL') || die();

class block_boaidp extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_boaidp');
    }

    function get_content() {
        global $CFG, $OUTPUT;

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
        $this->content->footer = '';

        // user/index.php expect course context, so get one if page has module context.
        $currentcontext = $this->page->context->get_course_context(false);

        if (! empty($this->config->text)) {
            $this->content->text = $this->config->text;
        }

        $this->content = '';
        if (empty($currentcontext)) {
            return $this->content;
        }
        if ($this->page->course->id == SITEID) {
            $this->context->text .= "site context";
        }

        if (isset($this->config) && isset($this->config->text) && ! empty($this->config->text)) {
            $this->content->text .= $this->config->text;
        }else{$this->content->text="";}

        return $this->content;
    }

    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats() {
        return array('all' => false,
                     'site' => true,
                     'site-index' => true,
                     'course-view' => true, 
                     'course-view-social' => false,
                     'mod' => true, 
                     'mod-quiz' => false);
    }

    public function instance_allow_multiple() {
          //return true;
          return false;
    }

    function has_config() {return true;}

    public function cron() {
            mtrace( "Hey, my cron script is running" );
             
                 // do something
                  
                      return true;
    }
}
