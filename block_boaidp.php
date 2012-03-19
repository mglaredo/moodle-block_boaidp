<?PHP  

/*
 * +----------------------------------------------------------------------+
 * | PHP Version 5                                                        |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2011 Miguel Gonzalez Laredo                            |
 * |                    mglaredo@ugr.es                                   |
 * |                    University of Granada                             |
 * |                                                                      |
 * | boaidp -- A Moodle Block for generating and editing                  | 
 * |           Course's Metadata for Data Providers by version OAI v2.0   |
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
 * @copyright Copyright (c) 2011 Miguel Gonzalez Laredo. Virtual Learning Center CEVUG, University of Granada
 * @license    http://opensource.org/licenses/gpl-3.0.html     GNU Public License
 * @author Miguel Gonzalez Laredo, mglaredo@ugr.es                     
 */

require_once ($CFG->libdir.'/dmllib.php');

/**
 * Course's Metadata Loading and Management for OAI Data Providers v2.0
 *
 * 
 * @author Miguel Gonzalez Laredo 
 * @version $Id$
 * @package block_boaidp
 **/
class block_boaidp extends block_base {
    
 
function has_config() {
return true;
}

function hide_header() {
  //Default, false--> the header is shown
  return FALSE;
}
  
/**
* Cleaning actions before delete the block from moodle 
 * (remove mdl_config records, etc.)
*  
*/
function before_delete(){
    $conf_vars_array = get_records_select('config','name like "%block_boaidp_%"','','name','','');
    
    if( !is_null($conf_vars_array) && $conf_vars_array!=FALSE )
    {
        foreach ($conf_vars_array as $conf){
            if( strcmp( substr($conf->name,0,  strlen('block_boaidp')),'block_boaidp') == 0 )
                    unset_config($conf->name,NULL);
        }
    }   

}

/**
* Cleaning actions before delete the block from moodle 
 * (remove mdl_config records, etc.)
*  
*/
function instance_config_save($data){
  global $CFG;
  $retval = true;
  
  parent::instance_config_save($data);     
  
  $params = array("id"=>"","instanceid"=>"","sesskey"=>"");
    
  foreach($params as $key=>$val){
    $params[$key] = required_param($key, 1, PARAM_INT);
  }
  $params["blockaction"] = "config";
  
  if( isset ($data->new_multiple_value) && $data->new_multiple_value<>0 ){
	$params[$data->new_multiple_value]=$data->new_multiple_value;
	$this->boaidp_add_value_toDB($params["id"],$data->new_multiple_value,/*null*/'');
	redirect($this->boaidp_get_instance_redirect_URL($params));
  }else{
      
      $this->boaidp_reset_notUpdableFields($data, $this->config);
      
      $this->boaidp_update_OAIrecord($this->instance->or_tab,$this->config);
      //var_dump($data); echo "<br/>"; var_dump($this->config); exit(0);
      return $retval;
  }
  //course/view.php?id=8&instanceid=76&sesskey=Wo44nNd0jL&
  
  
} 

//function add_value_toDB($provider,$fld_id,$value){
function boaidp_add_value_toDB($provider,$fld_id,$value){
            $record = new stdClass;
            $record->id = NULL;
            $record->provider = $provider;
            $record->field_id = $fld_id;
            $record->value = $value;
            
            insert_record($this->instance->mv_tab,$record);
}
// Own function, not overwritten
// associative array extra_params 
function boaidp_get_instance_redirect_URL($extra_params=''){
    global $CFG;
    
    $first = true;
    $target_url = $CFG->wwwroot.'/course/view.php?'; //.'&';  
    
    if( $extra_params != ''){
        foreach ($extra_params as $par => $val){
            if(!$first){
                $target_url .= '&';
            }else $first = false;
            
            $target_url .= urlencode($par).'='.urlencode($val);
        }
    }

    return $target_url;
}

function boaidp_clean_configs($scope){
	$env = strtoupper($scope);
	
	switch($env){
		case "INSTANCE":
			unset_config(' ');
			break; 
		case "BLOCK":
			unset_config('block_boaidp_step');
			unset_config('block_boaidp_schema');
			unset_config('block_boaidp_userAction');
			unset_config('block_boaidp_fieldName');
			break;
		default:
			unset_config(' ');
			unset_config('block_boaidp_step');
			unset_config('block_boaidp_schema');
			unset_config('block_boaidp_userAction');
			unset_config('block_boaidp_fieldName');
	}
}


function config_save($data) {
  global $CFG;
  
  $doSave = TRUE;
  
  parent::config_save($data);

  if(!isset($block_id))
    $block_id = required_param('block', 1, PARAM_INT);
  
  if(isset($data->block_boaidp_step)){   
	  if(!$this->boaidp_is_finished_step($data->block_boaidp_step))     
		redirect($this->boaidp_get_conf_redirect_URL($block_id, array('choose' => $CFG/*$data*/->block_boaidp_schema)));
	  else{
		
		if(isset($data->block_boaidp_fieldName))  
			$fieldName = $data->block_boaidp_fieldName;
		else
			$fieldName = $CFG->block_boaidp_fieldName;
		
		//Save if not cancelled
		if( isset($data->block_boaidp_userAction) && strcmp($data->block_boaidp_userAction,get_string('cancel'))<>0 ){		
			$fld_record = new stdClass;
			$fld_record->id = NULL;
			$fld_record->schema_id = $CFG->block_boaidp_schema;
			$fld_record->name = $fieldName;
			
			insert_record($this->instance->fld_tab,$fld_record);
		}else{
			$doSave = FALSE; //cancelled
		}
	   
	   //Lastly, clean config variables	to this block configuration
	   $this->boaidp_clean_configs("BLOCK"); 
	  }
	}

	return $doSave;
//  return false;
}

// Own function, not overwritten
// associative array extra_params 
function  boaidp_get_conf_redirect_URL($b_id='0', $extra_params=''){
    global $CFG;
    
    $first = true;
    $target_url = $CFG->wwwroot.'/'.$CFG->admin.'/block.php?block='.$b_id.'&';
    
    if( $extra_params != ''){
        foreach ($extra_params as $par => $val){
            if(!$first){
                $target_url .= '&';
            }else $first = false;
            
            $target_url .= urlencode($par).'='.urlencode($val);
        }
    }

    return $target_url;
}

function init() {
$this->title   = get_string('boaidp', 'block_boaidp');
$this->version = 2012021901; //2011051600;
$this->cron = 0; //1;

//$this->boaidp_initInstance();
//$this->requires  // Moodle version - copy from $version in the top-level version.php file.

}

// For now, nothing
function cron() {
  /*global $CFG;

  $course_tab = 'course';
  $oai_tab = 'oai_records';
  $pk = 'provider';
  $eol = '';

  $courses = get_records($course_tab,'','','id ASC','id,fullname,timecreated,format'); // Retrieve IDs in moodle

  foreach ($courses as $c){

    if (!record_exists($oai_tab, $pk, $c->id)){
        mtrace( "w", $eol );
        $oai_record = $this->boaidp_preload_record($CFG, $c); 
        insert_record($oai_tab,$oai_record);
    }

    mtrace( ".", $eol );

  }

  //mtrace( "\n BOAIDP BLOCK: cron finished..." );
 */
 return true;
}

function instance_allow_multiple() {
return false;
}

function instance_allow_config() {
return true;
}

function instance_create(){
   //function instance_create(){
  global $CFG, $COURSE;       // We intend to use the $CFG global variable
  $flag_returnID = true;
  
    $this->boaidp_initInstance();
    
    $this->instance->result = $this->boaidp_preload_OAIrecord($CFG, $COURSE);
    
    if( !record_exists($this->instance->or_tab,'provider',$COURSE->id) ){
        $this->instance->orID = insert_record($this->instance->or_tab, $this->instance->result,$flag_returnID); //New OAI record
    }
    
    $this->boaidp_generate_Metadata($COURSE->id, $this->instance->mv_tab, $this->instance->fld_tab,null,null,'id,schema_id,name');

    $this->instance->result = get_record($this->instance->or_tab, 'provider', $COURSE->id);

    if ($this->instance->result == false) 
            $this->content->footer = get_string('empty_record','block_boaidp');
    
}

function instance_delete(){
    $courseid = $this->instance->pageid;
    
    delete_records($this->instance->mv_tab,"provider",$courseid);
    delete_records($this->instance->or_tab,"provider",$courseid);
    
    return true;
}

function specialization() {
//function instance_create(){
  global $CFG, $COURSE;       // We intend to use the $CFG global variable

    $this->boaidp_initInstance();

    $this->content->footer = get_string('last_operations','block_boaidp');

    // Getting record for current course
    $this->instance->result = get_record($this->instance->or_tab, 'provider', $COURSE->id);

    // Create if not exists and retrieve again
/* KK
     if ($this->instance->result == false ){ 
        $this->instance->result = $this->boaidp_preload_OAIrecord($CFG, $COURSE);
        
        if( !record_exists($this->instance->or_tab,'provider',$COURSE->id) ){
            insert_record($this->instance->or_tab, $this->instance->result); //New OAI record
        }
        $this->boaidp_generate_Metadata($COURSE->id, $this->instance->mv_tab, $this->instance->fld_tab,null,null,'id,schema_id,name');

        $this->instance->result = get_record($this->instance->or_tab, 'provider', $COURSE->id);

        if ($this->instance->result == false) 
                $this->content->footer = get_string('empty_record','block_boaidp');
    }
KK */    
    // First time config_instance isn't setted
    if ( !isset($this->config) ) {
        $this->config = new stdClass;
        /*$this->config = */ $this->boaidp_load_config($this->instance->result, $this->config);
        $this->content->footer = $this->content->footer.get_string('config_not_set','block_boaidp');
    }else{ // Set not updatables fields in config_instance form; from DB again
        //$this->config = $this->boaidp_load_config(/*$this->instance->result,*/$this->config); //Always reload config || It doesn't work
/*$this->config = */ $this->boaidp_reset_notUpdableFields($this->instance->result, $this->config);
        $this->content->footer = $this->content->footer.get_string('config_set','block_boaidp');
    }
} // <end>specialization()</end>
  

function get_content() {       
  global  $COURSE;
  
    //update_record($this->instance->or_tab,$this->config);
    //$this->config = $this->boaidp_load_config(/*$this->instance->result, */$this->config);
    $this -> boaidp_update_OAIrecord($this->instance->or_tab,$this->config);
    $this -> boaidp_update_Metadata($COURSE->id, $this->instance->mv_tab, $this->instance->fld_tab,null,null,'id,schema_id,name',$this->config);
    
    return $this->content;
} // <end>get_content()</end>

  
function boaidp_initInstance(){
    global $COURSE;
    
    if (!isset($this->instance)){ 
        $this->instance = new stdClass;
    }
        $this->ctrl_prefix = 'id=';
        $this->instance->or_tab =  'block_boaidp_oai_records';
        $this->instance->mv_tab =  'block_boaidp_oai_metadata_values';
            $this->instance->fld_tab = 'block_boaidp_oai_metadata_fields';
        $this->instance->sch_tab = 'block_boaidp_oai_metadata_schema';
    
    // Initialization of variables not setted.
    if (!isset($this->content)){
            $this->content = new stdClass;
            $this->content->text = '';
    }

    if (!isset($this->instance->result)) 
        $this->instance->result = new stdClass;
    
    if(!isset($this->bname))
        $this->bname = 'block_boaidp';
    
    if(!isset($this->pre))
        $this->pre = 'mdl_';
     
    if(isset($this->instance) && !isset($this->instance->orID)){
         $record = get_record_select($this->instance->or_tab, 'provider='.$COURSE->id,'id');
         
         if( isset($record->id) )
            $this->instance->orID = $record->id;
    }
    
}

// <Modified-Added June 7th, 2011>
function boaidp_reset_notUpdableFields($source,&$target){
    
    $vars = array("id","provider","url","enterdate","datestamp","oai_set","oai_identifier","deleted");
 
    foreach($vars as $val){
        if( isset($source->$val) /*KK && isset($target->$val)*/ )
            $target->$val = $source->$val;
      }
    
    /*$target->id             = $source->id;
    $target->provider       = $source->provider;   
    $target->url            = $source->url;    
    $target->enterdate      = $source->enterdate;        
    $target->datestamp      = $source->datestamp; //date('d-m-y',$source->datestamp);  */      

}




// 
// Own function, not overwritten
// Checks if "Finish Step" of configuration has got
function boaidp_is_finished_step($str){
    return strcmp(strtoupper($str),'FINISH')==0;
}

        
/**
* Get fields from metadata schema 
*  
* @param string	$tabs The tab that holds the fields for metadata schema 
* @param string	$schema_id Key for schema
* @param string	$retrive_fields Columns for retrieving from $tab
*/
function boaidp_get_FieldsForSchema(&$q,$tabs='mdl_block_boaidp_oai_metadata_fields fld', 
        $cond='fld.id=mv.field_id', $schema_cond='fld.schema_id=fld.schema_id', 
        $retrieve_fields='fld.id, fld.schema_id, fld.name'){

    $q = "SELECT ".$retrieve_fields. " FROM ".$tabs. " WHERE ". $cond;

    if (!is_null($schema_cond))
        $q .=  ' AND '.$schema_cond;                                

    $rs = get_records_sql($q);

    
    return  $rs;

}


/**
* Get fields values from metadata schema 
*  
* @param string	$tabs The tab that holds the fields for metadata schema 
* @param string	$schema_id Key for schema
* @param string	$retrive_fields Columns for retrieving from $tab
*/
function boaidp_get_FieldsValuesForSchema(&$q,$tabs='mdl_block_boaidp_oai_metadata_fields fld, mdl_block_boaidp_oai_metadata_values mv', 
            $cond='fld.id=mv.field_id', $provider_cond='mv.provider=mv.provider', 
            $schema_cond='fld.schema_id=fld.schema_id', $retrieve_fields='mv.id, fld.schema_id, fld.name, mv.value',
            $order='fld.name'){

    $q = "SELECT ".$retrieve_fields. " FROM ".$tabs. " WHERE ". $cond;
    if (!is_null($provider_cond))
        $q .= ' AND '.$provider_cond;

    if (!is_null($schema_cond))
        $q .=  ' AND '.$schema_cond;                                

    $rs = get_records_sql($q.' order by '.$order);

    return $rs;

}

/**
* Generate empty values from metadata schema fields for course on DB
*  
* @param int	$provider Id of course  
* @param string	$mv_tab The tab that holds the values for metadata schema         
* @param string	$fld_tab The tab that holds the fields from metadata schema 
* @param string	$schema_id Key for schema
* @return array(object) with fields generated
*/
function boaidp_generate_Metadata($provider, $mv_tab, $fld_tab, $sch_field,$sch_id,$retrieve_fields='id,schema_id,name'){

    if(is_null($sch_field) || is_null($sch_id))
        $sch_field=''; $sch_id='';

    $fields = get_records($fld_tab,$sch_field,$sch_id,'',$retrieve_fields);
    $new_val = new stdClass();
    $new_val->provider= $provider; //constant for this course
    //$i=0;
   // echo 'boaidp_generateMetadata - debug - '.$i++;
    
    if($fields){
 
        foreach ($fields as $sch=>$flds)
        { 
            $new_val->field_id=strval($flds->id);
            $new_val->value =  '';
            if( !record_exists($mv_tab,'field_id',$new_val->field_id,'provider',$new_val->provider) ) //Only create if any don't exist
                insert_record($mv_tab,$new_val);
        }  
    }

    return $fields;

}


/**
* Update values for oai_record of course/provider on DB
* according to the latest Block's config values (passed as argument)
*  
* @param string	$or_tab The tab that holds the values for oai_record
* @param string	$source An object containing values to apply to record, containing a key field called "id" 
* @return array(object) with fields generated
*/
function boaidp_update_OAIrecord($or_tab,$source){
        
    $target =  new stdClass;
    $result = true;
    
    $target->id = $this->instance->orID;
    
    $vars = array(/*"id",*/"provider","url","enterdate","datestamp","deleted","oai_identifier","oai_set");
//KK var_dump($source);
    foreach($vars as $val){
        if( isset($source->$val) /*KK && isset($target->$val) */)
            $target->$val = $source->$val;
      }
      
        //Update of oai fields only
        /*$obj->id = $source->id;
        $obj->provider = $source->provider;
        $obj->url = $source->provider;
        $obj->enterdate =  $source->enterdate;
        $obj->oai_identifier = $source->oai_identifier;
        $obj->oai_set = $source->oai_set;
        $obj->datestamp =  $source->datestamp;
        $obj->deleted = $source->deleted; */
        //if ( isset($target->id) )
      
      $result = update_record($or_tab,$target);
        
        return $result;
}

/**
* Update values from metadata schema fields for course on DB
* according to the latest Block's config values (passed as argument)
*  
* @param int	$provider Id of course  
* @param string	$mv_tab The tab that holds the values for metadata schema         
* @param string	$fld_tab The tab that holds the fields from metadata schema 
* @param string	$schema_id Key for schema
* @return array(object) with fields generated
*/
function boaidp_update_Metadata($provider, $mv_tab, $fld_tab, $sch_field,$sch_id,$retrieve_fields='id,schema_id,name', $our_cfg_obj){

    if(is_null($sch_field) || is_null($sch_id))
        $sch_field=''; $sch_id='';

    $fields = get_records($fld_tab,$sch_field,$sch_id,'',$retrieve_fields);
    $new_val = new stdClass();
    $new_val->provider= $provider; //constant for this course

    if($fields){
        foreach ($fields as $sch=>$flds)
        {
            $fld_name = $flds->name;
            
            $new_val->field_id=strval($flds->id); //Adding id of field type 
            $q = 'select id from '.$this->pre.$mv_tab.' where '.'field_id='.$new_val->field_id.' and provider='.$new_val->provider;
            $records = get_records_sql($q); //get_recordset_select($mv_tab, 'field_id='.$new_val->field_id.' and provider='.$new_val->provider,'','id');
            
          
            if($records){ //Switch (update or insert) depending on record existence or not
                foreach($records as $reg){
                    $ctrl_id = $this->ctrl_prefix.$reg->id;
                    $new_val->id = substr($ctrl_id,stripos($this->ctrl_prefix,$ctrl_id)+strlen($this->ctrl_prefix));
                    // Is it on CFG?    
                    if( isset($our_cfg_obj->$ctrl_id) ){ 
                        $new_val->value =  $our_cfg_obj->$ctrl_id; //getting value from CFG associated to ID                  
                    }else{
                        $new_val->value = '';
                    }
                    //Do update or insert
                    if( record_exists($mv_tab,'id',$new_val->id,'provider',$new_val->provider) )
                        update_record($mv_tab,$new_val);
                    else
                        insert_record($mv_tab,$new_val);
                }
            }else{
                $new_val->id = NULL;
                insert_record($mv_tab,$new_val);
            }  
 
        }  
    }

    return $fields;

}


function boaidp_load_config($source,&$target){
    global /*$CFG,*/$COURSE;

    $this->boaidp_reset_notUpdableFields($source, $target);

    //Adding dynamic fields for schemas
    $tabs =  $this->pre.$this->instance->fld_tab.' fld,'.$this->pre.$this->instance->mv_tab.' mv ';
    $q = '';

    $fields = $this->boaidp_get_FieldsValuesForSchema(&$q, $tabs,'fld.id=mv.field_id','mv.provider='.$COURSE->id,null,'mv.id, fld.schema_id, fld.name, mv.value');  //array('id','url','provider','enterdate','datestamp','dc_date');
 
    if ($fields){ // If not FALSE the result
        foreach ($fields as $sch=>$flds)
        {
            //$name =  strval($flds->name);
            $id =  strval($flds->id);
            $value =  strval($flds->value);
            //echo $name.' <<--.-->>'.$value;

            //$target->$name = $value;
            $ctrl_id = $this->ctrl_prefix.$id;
            $target->$ctrl_id = $value;
        }
    }
    //return $target;
}
    // </Modified-Added June 7th, 2011>
    
    function boaidp_preload_OAIrecord($cfg_obj, $course_obj){
        //global $CFG; //, $COURSE;     // We intend to use the $CFG global variable
        
        $obj =  new stdClass;       //$this->instance->result->id = null;
        $obj->provider = $course_obj->id;
        $obj->url = $cfg_obj->wwwroot.'/course/view.php?id='.$course_obj->id;
        $obj->enterdate =  time();
        //$obj->oai_identifier = $course_obj->id;
        $obj->oai_set = null;
        $obj->datestamp =  time();
        $obj->deleted = 'false';
        //$obj->dc_date =  $course_obj->timecreated; //time();
        
        /*$obj->dc_title = $course_obj->fullname;
        $obj->dc_creator = 'CEVUG';
        $obj->dc_subject = '';
        $obj->dc_description = '';
        $obj->dc_contributor = '';
        $obj->dc_publisher = '';

        $obj->dc_type = 'On-line Course';
        $obj->dc_format = $course_obj->format;
        $obj->dc_identifier = ''; // $CFG->wwwroot.'/course/view.php?id='.$COURSE->id;
        $obj->dc_source = 'MOODLE e-Learning Platform';
        $obj->dc_language = 'es';            
        $obj->dc_relation = ' ';
        $obj->dc_coverage = 'European Union';
        $obj->dc_rights = 'CC BY-NC-SA 2.5';*/
       
        return $obj;
    }
    
	function block_boaidp_get_starting_step(){
		return 'ChoosingSchema';
	}
	
	function block_boaidp_get_next_step($str){
		$current = strtoupper($str);
		$next = " ";

		switch ($str) {
			case "ChoosingSchema":
				$next = "ChoosingField";
			break;
			case "ChoosingField":
			//$next = "EnteringValue";
				$next = "Finish";
			break; 
			case "EnteringValue":
				$next = "Finish";
			break;   
			default: 
				$next = "Finish";
		}
		return $next;
	}
	
	// Own function, not overwritten
	// Get "Finish Step" string
	function block_boaidp_get_finish_step(){
		return 'Finish';
	}
} //  <end> class block_boaidp </end>

?>
