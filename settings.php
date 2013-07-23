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
$block_id='block/boaidp';
$block_name='block_boaidp';
$nl = "\r\n";

$dayChoices = array();
for($i= 1; $i <= 31; $i++){
	$dayChoices[$i] = $i;
	
	if($i<=12)
		$monthChoices[$i]=$i;
}


$settings->add(new admin_setting_heading($block_id, get_string('headerconfig', $block_name), get_string('descconfig', $block_name)));

$settings->add(new admin_setting_configtextarea($block_name.'/dcfields', get_string('dcfields', $block_name),
					get_string('dcfieldsDesc', $block_name), 
					//"dc_title:dc_creator:dc_subject:dc_description:dc_contributor:dc_publisher:dc_date:dc_type:dc_format:dc_identifier:dc_source", 
					//"dc_title\ndc_creator\ndc_subject",
					"dc_title".$nl."dc_creator".$nl."dc_subject",
					PARAM_TEXT, '50', '10'));   

$settings->add( new admin_setting_configselect($block_name.'/earliestDay', get_string('earliestDay', $block_name), get_string('earliestDay_desc', $block_name),
				'', $dayChoices));
$settings->add(new admin_setting_configselect($block_name.'/earliestMonth', get_string('earliestMonth', $block_name), get_string('earliestMonth_desc', $block_name),
				'', $monthChoices));
										
$settings->add(new admin_setting_configtext($block_name.'/earliestYear', get_string('earliestYear', $block_name), get_string('earliestYear_desc', $block_name),
				'', PARAM_TEXT, 4));				

