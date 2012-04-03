<?php

 
/**
 * TODO
 *
 * @package   block_boaidp
 * @copyright Miguel González laredo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
defined('MOODLE_INTERNAL') || die();
 
$plugin->version   = 2012031901;
$plugin->requires  = 2007101550; //minimum 1.9.5  // See http://docs.moodle.org/dev/Moodle_Versions
$plugin->cron      = 0;
$plugin->component = 'block_boaidp';
$plugin->maturity  = MATURITY_RC; //BETA; //MATURITY_STABLE;
$plugin->release   = '1.3.2';
 
$plugin->dependencies = array(
    'mod_forum' => ANY_VERSION,
    'mod_data'  => TODO
);
