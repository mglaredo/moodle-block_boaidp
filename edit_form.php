<?php
/**
 * File: BOAIDP Course block edition
 * @package MOODLE-BLOCK-BOAIDP
 */ 

/**
 * Edit form's class: changing metadata values from course's block
 */
class block_boaidp_edit_form extends block_edit_form {
	/**
	 * @var	string	contains for Moodle usage
	 */
	public $bname = 'block_boaidp';
	/**
    * @var string New Line string
    */
	public $nl = "\r\n";
	/**
    * @var string Prefix needed for naming config values
    */
	public $cfg_prefix = "config_";
	
	/*public*/ 
	/**
	 * Generate Moodle Form for customize our course's block
	 */
    function specific_definition($mform) {
		global $COURSE;
		
        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('boaidp',$this->bname));
        
        // Obtaining from GLOBAL BLOCK'S CONFIG
        $dcfields = get_config($this->bname,'dcfields');
        if (empty($dcfields))
			$dcfields = "empty";
        $mform->addElement('static', 'DCMES', get_string('dcmes',$this->bname), str_replace($this->nl,"::",$dcfields));
        
        $mform->addElement('static', 'config_id', get_string('id',$this->bname), $COURSE->id);
        $mform->addElement('date_time_selector', 'config_enterdate', get_string('enterdate',$this->bname));
        $mform->addElement('date_time_selector', 'config_datestamp', get_string('datestamp',$this->bname));

		//"Dynamic" metadata fields
        $fields = $this->get_fields($dcfields);
		foreach($fields as $cfg){
			$mform->addElement('text', $this->cfg_prefix.$cfg, get_string($cfg,$this->bname));
			$mform->setDefault($this->cfg_prefix.$cfg, get_string('defaultvalue', $this->bname));
			$mform->setType($this->cfg_prefix.$cfg, PARAM_MULTILANG); 
		}

    }
    
    /*public*/ 
    /**
	 * Custom function: facilitate obtaining the dynamic/predefined DCMES as array
	 * @return array containing DCMES fields
	 */
	 function get_fields($dcfields=null){
		
		if(!$dcfields){ // If not GLOBAL setting obtained
			$fields = array(
							//"id"=>"id","url"=>"url",
							"provider"=>"provider",
							//"enterdate"=>"enterdate",
							"oai_identifier"=>"oai_identifier",
							"oai_set"=>"oai_set",
							//"datestamp"=>"datestamp",
							"deleted"=>"deleted",
							"dc_title"=>"dc_title",
							"dc_creator"=>"dc_creator",
							"dc_subject"=>"dc_subject",
							"dc_description"=>"dc_description",
							"dc_contributor"=>"dc_contributor",
							"dc_publisher"=>"dc_publisher",
							"dc_date"=>"dc_date",
							"dc_type"=>"dc_type",
							"dc_format"=>"dc_format",
							"dc_identifier"=>"dc_identifier",
							"dc_source"=>"dc_source",
							"dc_language"=>"dc_language",
							"dc_relation"=>"dc_relation",
							"dc_coverage"=>"dc_coverage",
							"dc_rights"=>"dc_rights",
							);			
			}else{
				$fields= explode($this->nl,$dcfields);
			}
							
			return $fields;
		}
		
}
