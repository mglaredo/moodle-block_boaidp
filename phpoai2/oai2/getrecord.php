<?

// parse and check arguments
foreach($args as $key => $val) {

	switch ($key) { 
		case 'identifier':
			$identifier = $val; 
			if (!is_valid_uri($identifier)) {
				$errors .= oai_error('badArgument', $key, $val);
			}
			break;

		case 'metadataPrefix':
			if (is_array($METADATAFORMATS[$val])
					&& isset($METADATAFORMATS[$val]['myhandler'])) {
				$metadataPrefix = $val;
				$inc_record  = $METADATAFORMATS[$val]['myhandler'];
			} else {
				$errors .= oai_error('cannotDisseminateFormat', $key, $val);
			}
			break;

		default:
			$errors .= oai_error('badArgument', $key, $val);
	}
}

if (!isset($args['identifier'])) {
	$errors .= oai_error('missingArgument', 'identifier');
}
if (!isset($args['metadataPrefix'])) {
	$errors .= oai_error('missingArgument', 'metadataPrefix');
} 

// remove the OAI part to get the identifier
if (empty($errors)) {
	$id = str_replace($oaiprefix, '', $identifier); 
	if ($id == '') {
		$errors .= oai_error('idDoesNotExist', '', $identifier);
	}

	$query = selectallQuery($id); 
	$res = $db->query($query);

	if (DB::isError($res)) {
		if ($SHOW_QUERY_ERROR) {
			echo __FILE__.','.__LINE."<br />";
			echo "Query: $query<br />\n";
			die($db->errorNative());
		} else {
			$errors .= oai_error('idDoesNotExist', '', $identifier); 
		}
	} elseif (!$res->numRows()) {
		$errors .= oai_error('idDoesNotExist', '', $identifier); 
	}
}

// break and clean up on error
if ($errors != '') {
	oai_exit();
}

$output .= "  <GetRecord>\n";

$num_rows = $res->numRows();
if (DB::isError($num_rows)) {
	if ($SHOW_QUERY_ERROR) {
		echo __FILE__.','.__LINE."<br />";
		echo "Query: $query<br />\n";
		die($db->errorNative());
	}
}
if ($num_rows) {
	$record = $res->fetchRow();
	if (DB::isError($record)) {
		if ($SHOW_QUERY_ERROR) {
			echo __FILE__.','.__LINE."<br />";
			die($db->errorNative());
		}
	}
	
	$identifier = $oaiprefix.$record[$SQL['identifier']];;

	$datestamp = formatDatestamp($record[$SQL['datestamp']]); 

	if (isset($record[$SQL['deleted']]) && ($record[$SQL['deleted']] == 'true') && 
		($deletedRecord == 'transient' || $deletedRecord == 'persistent')) {
		$status_deleted = TRUE;
	} else {
		$status_deleted = FALSE;
	}

// print Header
	$output .= 
'  <record>'."\n";
	$output .= 
'  <header';
	if ($status_deleted) {
		$output .= ' status="deleted"';
	}  
	$output .='>'."\n";

	// use xmlrecord since we include stuff from database;
	$output .= xmlrecord($identifier, 'identifier', '', 3);
	$output .= xmlformat($datestamp, 'datestamp', '', 3);
	if (!$status_deleted) 
		$output .= xmlrecord($record[$SQL['set']], 'setSpec', '', 3);
	$output .= 
'   </header>'."\n"; 

// return the metadata record itself
	if (!$status_deleted) 
		include('oai2/'.$inc_record); 

	$output .= 
'  </record>'."\n"; 
} 
else {
	// we should never get here
	oai_error('idDoesNotExist');
}

// End GetRecord
$output .= 
' </GetRecord>'."\n"; 
?>
