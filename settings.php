<?php
/**
 * GLOBAL SETTING for Block
 * @package MOODLE-BLOCK-BOAIDP
 */
 
/*
 * More information to develop this feature on:
 * - http://docs.moodle.org/dev/Admin_settings#Individual_settings
 * - http://phpdocs.moodle.org/HEAD/core/admin/admin_setting.html
 */
defined('MOODLE_INTERNAL') || die();

$nl = "\r\n";
$settings->add(new admin_setting_heading('block/boaidp', get_string('headerconfig', 'block_boaidp'), get_string('descconfig', 'block_boaidp')));

$settings->add(new admin_setting_configtextarea('block_boaidp/dcfields', get_string('dcfields', 'block_boaidp'),
					get_string('dcfieldsDesc', 'block_boaidp'), 
					//"dc_title:dc_creator:dc_subject:dc_description:dc_contributor:dc_publisher:dc_date:dc_type:dc_format:dc_identifier:dc_source", 
					//"dc_title\ndc_creator\ndc_subject",
					"dc_title".$nl."dc_creator".$nl."dc_subject",
					PARAM_TEXT, '50', '10'));   

