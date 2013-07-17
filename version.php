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

$plugin->version   = 2013071700;        // The current plugin version (Date: YYYYMMDDXX)
$plugin->requires  = 2012112900;        // Requires this Moodle version
$plugin->component = 'block_boaidp'; 	// Full name of the plugin (used for diagnostics)
$plugin->cron = 0;
$plugin->maturity = MATURITY_BETA; 		// MATURITY_ALPHA, MATURITY_BETA, MATURITY_RC, MATURITY_STABLE 
$plugin->release   = '2.0';
