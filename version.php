<?php

 
/**
 * TODO
 *
 * @package   block_boaidp
 * @copyright Miguel González laredo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
defined('MOODLE_INTERNAL') || die();
 
$plugin->version   = 2012021901;
//$plugin->requires  = TODO; // See http://docs.moodle.org/dev/Moodle_Versions
$plugin->cron      = 0;
$plugin->component = 'block_boaidp';
$plugin->maturity  = BETA; //MATURITY_STABLE;
$plugin->release   = '1.3';
 
$plugin->dependencies = array(
    'mod_forum' => ANY_VERSION,
    'mod_data'  => TODO
);
