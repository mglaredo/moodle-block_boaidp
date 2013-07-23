<?

// parse and check arguments
foreach($args as $key => $val) {

	switch ($key) { 
		case 'identifier':
			$identifier = $val; 
			break;

		case 'metadataPrefix':
		// only to be compatible with VT explorer
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

if (isset($args['identifier'])) {
	// remove the OAI part to get the identifier
	$id = str_replace($oaiprefix, '', $identifier); 

	$query = idQuery($id);
	$res = $db->query($query);
	if (DB::isError($res)) {
		if ($SHOW_QUERY_ERROR) {
			echo __FILE__.','.__LINE."<br />";
			echo "Query: $query<br />\n";
			die($db->errorNative());
		} else {
			$errors .= oai_error('idDoesNotExist', 'identifier', $identifier);
		}
	} elseif (!$res->numRows()) {
		$errors .= oai_error('idDoesNotExist', 'identifier', $identifier);
	}    
}

//break and clean up on error
if ($errors != '') {
	oai_exit();
}

// currently it is assumed that an existing identifier
// can be served in all available metadataformats...
// 
if (is_array($METADATAFORMATS)) {
	$output .= " <ListMetadataFormats>\n";
	foreach($METADATAFORMATS as $key=>$val) {
		$output .= "  <metadataFormat>\n";
		$output .= xmlformat($key, 'metadataPrefix', '', 3);
		$output .= xmlformat($val['schema'], 'schema', '', 3);
		$output .= xmlformat($val['metadataNamespace'], 'metadataNamespace', '', 3);
		$output .= "  </metadataFormat>\n";
	}
	$output .= " </ListMetadataFormats>\n"; 
}
else {
	$errors .= oai_error('noMetadataFormats'); 
	oai_exit();
}

?>
