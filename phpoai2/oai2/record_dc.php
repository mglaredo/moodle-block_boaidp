<?

// this handles unqualified DC records, but can be also used as a sample
// for other formats.
// just specify the next variable according to your own metadata prefix
// change output your metadata records further below.

// please change to the according metadata prefix you use 
$prefix = 'oai_dc';
$record_prefix = "dc";
// you do need to change anything in the namespace and schema stuff
// the correct headers should be created automatically

$output .= 
'   <metadata>'."\n";

$output .= metadataHeader($prefix);

//17/05/2011
//<ADDED BY CEVUG>
if (empty($errors)) {
            /*
            $md_query = selectallMetadataForQuery($record[$SQL['mv_key_filter']],$prefix);
            $md_res = $db->get_records_sql($md_query,null);//query($query);   
			*/
			$md_res = $record[$SQL['mv_value']];
            if ($md_res instanceof dml_exception) {
                    if ($SHOW_QUERY_ERROR) {
                            echo __FILE__.",". __LINE__."<br />";
                            echo "Query ERROR: $md_res<br />\n";
                            var_dump($record);
                            die();
                    } else {
                            $errors .= oai_error('noRecordsMatch',$md_query); 
                    }
            } else {
                    $md_num_rows = count($md_res);
                    if ($md_num_rows instanceof dml_exception || !$md_num_rows) {
                            if ($SHOW_QUERY_ERROR) {
                                    echo __FILE__.",". __LINE__."<br />";
                                    die($db->errorNative());
                            } 
                    }
                    if (!$md_num_rows) {
                            $errors .= oai_error('noRecordsMatch',$md_query); 
                    }
            }
    }
    
    // break and clean up on error
    if ($errors != '') {
            oai_exit();
    }
    
    $indent = 6;
        
    //Getting Moodle Block instance for passed course "id" and its configdata
    //(See -> https://moodle.org/mod/forum/discuss.php?d=129799#p752296)
	$instance = $DB->get_record('block_instances', array('blockname'=>'boaidp', 'id'=>$record[$SQL['block_id']]), '*', MUST_EXIST); 
	$block = block_instance('boaidp', $instance);
	$block->config;
	$configdata = (array) $block->config;
	//var_dump($configdata);
	//die();
	foreach ($configdata as $key=>$cfg) 
	{ 	
		/*if(substr_count($key,"date")>0) // If exists It is date field
			$cfg = date($moodle_datestamp_format, $cfg); */
		if(substr_count($key,$record_prefix."_")>0){ // Only "dc_%" fields
			$cleanKey = str_replace($record_prefix."_","",$key);
			$output .= xmlrecord($cfg,$cleanKey,"", $indent);
		}
	}
	 
	 
//</ADDED BY CEVUG>
// please change according to your metadata format
/*
$indent = 6;
// $output .= xmlrecord($record[$SQL['md_match_id']], 'provider','', $indent);
$output .= xmlrecord($record['dc_title'], 'dc:title', '', $indent);
$output .= xmlrecord($record['dc_creator'],'dc:creator', '', $indent);
$output .= xmlrecord($record['dc_subject'], 'dc:subject', '', $indent);
$output .= xmlrecord($record['dc_description'], 'dc:description', '', $indent);
$output .= xmlrecord($record['dc_publisher'], 'dc:publisher', '', $indent);
$output .= xmlrecord($record['dc_contributor'], 'dc:contributor', '', $indent);
$output .= xmlrecord($record['dc_date'], 'dc:date', '', $indent);
$output .= xmlrecord($record['dc_type'], 'dc:type', '', $indent);
$output .= xmlrecord($record['dc_format'], 'dc:format', '', $indent);
$output .= xmlrecord($record['dc_identifier'], 'dc:identifier', '', $indent);
$output .= xmlrecord($record['dc_source'], 'dc:source', '', $indent);
$output .= xmlrecord($record['dc_language'], 'dc:language', '', $indent);
$output .= xmlrecord($record['dc_relation'], 'dc:relation', '', $indent);
$output .= xmlrecord($record['dc_coverage'], 'dc:coverage', '', $indent);
$output .= xmlrecord($record['dc_rights'], 'dc:rights', '', $indent);
*/

// Here, no changes need to be done
$output .=           
'     </'.$prefix;
if (isset($METADATAFORMATS[$prefix]['record_prefix'])) {
	$output .= ':'.$METADATAFORMATS[$prefix]['record_prefix'];
}
$output .= ">\n";
$output .= 
'   </metadata>'."\n";
?>
