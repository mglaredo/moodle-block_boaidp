<?php

class block_boaidp_edit_form extends block_edit_form {

	protected $bname = 'block_boaidp';
	 
    protected function specific_definition($mform) {
		global $COURSE;
		
        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('boaidp',$this->bname));
        $mform->addElement('static', 'config_id', get_string('id',$this->bname),$COURSE->id);
        $mform->addElement('date_time_selector', 'config_enterdate', get_string('enterdate',$this->bname));
        $mform->addElement('date_time_selector', 'config_datestamp', get_string('datestamp',$this->bname));

        $fields = $this->get_fields();
		foreach($fields as $cfg=>$str){
			$mform->addElement('text', $cfg, get_string($str,$this->bname));
			$mform->setDefault($cfg, get_string('defaultvalue',$this->bname));
			$mform->setType($cfg, PARAM_MULTILANG); 	
		}
		
		/* 
		$mform->addElement('text', 'config_title', get_string('id',$bname));
		$mform->setDefault('config_title', 'default value');
		$mform->setType('config_title', PARAM_MULTILANG);
		*/
    }
    
    protected function get_fields(){
		$fields = array(
						//"config_id"=>"id","config_url"=>"url",
						"config_provider"=>"provider",
						//"config_enterdate"=>"enterdate",
						"config_oai_identifier"=>"oai_identifier",
						"config_oai_set"=>"oai_set",
						//"config_datestamp"=>"datestamp",
						"config_deleted"=>"deleted",
						"config_dc_title"=>"dc_title",
						"config_dc_creator"=>"dc_creator",
						"config_dc_subject"=>"dc_subject",
						"config_dc_description"=>"dc_description",
						"config_dc_contributor"=>"dc_contributor",
						"config_dc_publisher"=>"dc_publisher",
						"config_dc_date"=>"dc_date",
						"config_dc_type"=>"dc_type",
						"config_dc_format"=>"dc_format",
						"config_dc_identifier"=>"dc_identifier",
						"config_dc_source"=>"dc_source",
						"config_dc_language"=>"dc_language",
						"config_dc_relation"=>"dc_relation",
						"config_dc_coverage"=>"dc_coverage",
						"config_dc_rights"=>"dc_rights",
						);
			
			return $fields;
		}
}
