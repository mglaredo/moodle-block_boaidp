<? 
// parse and check arguments
foreach($args as $key => $val) {

	switch ($key) { 
		case 'resumptionToken':
			$resumptionToken = $val;
			$errors .= oai_error('badResumptionToken', $key, $val); 
			break;

		default:
			$errors .= oai_error('badArgument', $key, $val);
	}
}

// break and clean up on error
if ($errors != '') {
	oai_exit();
}

if (is_array($SETS)) {
	$output .= "  <ListSets>\n";
	foreach($SETS as $key=>$val) {
		$output .= "   <set>\n";
		$output .= xmlformat($val['setSpec'], 'setSpec', '', 4);
		$output .= xmlformat($val['setName'], 'setName', '', 4);
		if (isset($val['setDescription']) && $val['setDescription'] != '') {
			$output .= "    <setDescription>\n";
			$prefix = 'oai_dc';
			$output .= metadataHeader($prefix);
			$output .= xmlrecord($val['setDescription'], 'dc:description', '', 7);
			$output .=           
			'     </'.$prefix;
			if (isset($METADATAFORMATS[$prefix]['record_prefix'])) {
				$output .= ':'.$METADATAFORMATS[$prefix]['record_prefix'];
			}
			$output .= ">\n";
			$output .= "    </setDescription>\n";
		}
		$output .= "   </set>\n";
	}
	$output .= "  </ListSets>\n"; 
}
else {
	$errors .= oai_error('noSetHierarchy'); 
	oai_exit();
}

?>
