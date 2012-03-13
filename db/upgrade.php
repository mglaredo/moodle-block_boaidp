<?php 

// This file keeps track of upgrades to
// the BOAIDP module
//

function xmldb_block_boaidp_upgrade($oldversion) {

    global $CFG, $THEME, $db;

    $result = true;

    if ($result && $oldversion < 2011051600) {

    /// Define table oai_metadata_schema to be created
        $table = new XMLDBTable('oai_metadata_schema');

    /// Adding fields to table oai_metadata_schema
        $table->addFieldInfo('id', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null);
        $table->addFieldInfo('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, null, null);
        $table->addFieldInfo('prefix', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, null, null);

    /// Adding keys to table oai_metadata_schema
        $table->addKeyInfo('primary', XMLDB_KEY_PRIMARY, array('id'));

    /// Launch create table for oai_metadata_schema
        $result = $result && create_table($table);
    }
    
    if ($result && $oldversion  < 2011051600) {

  /// Define table oai_metadata_fields to be created
        $table = new XMLDBTable('oai_metadata_fields');

    /// Adding fields to table oai_metadata_fields
        $table->addFieldInfo('id', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null);
        $table->addFieldInfo('schema_id', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, null);
        $table->addFieldInfo('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, null, null);

    /// Adding keys to table oai_metadata_fields
        $table->addKeyInfo('primary', XMLDB_KEY_PRIMARY, array('id'));

    /// Launch create table for oai_metadata_fields
        $result = $result && create_table($table);
    }

    
    if ($result && $oldversion  < 2011051600) {
    /// Define table oai_metadata_values to be created
        $table = new XMLDBTable('oai_metadata_values');

    /// Adding fields to table oai_metadata_values
        $table->addFieldInfo('id', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null);
        $table->addFieldInfo('provider', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, null, null);
        $table->addFieldInfo('field_id', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, null);
        $table->addFieldInfo('value', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, null, null);

    /// Adding keys to table oai_metadata_values
        $table->addKeyInfo('primary', XMLDB_KEY_PRIMARY, array('id'));

    /// Launch create table for oai_metadata_values
        $result = $result && create_table($table);
    }   
    
    //Added february 19th
    if ($result && $oldversion < 2012021900) {

    /// Define field ismultiple to be added to oai_metadata_fields
        $table = new XMLDBTable('oai_metadata_fields');
        $field = new XMLDBField('ismultiple');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '1', 'name');
    /// Launch add field ismultiple
        $result = $result && add_field($table, $field);
    }
    
        //Added february 20th
    if ($result && $oldversion < 2012021901) {

    /// Define field id to be dropped from oai_records
        $table = new XMLDBTable('oai_records');
        
        $fields = array('dc_title','dc_creator','dc_subject','dc_description','dc_contributor','dc_publisher','dc_date',
                        'dc_type','dc_format','dc_identifier','dc_source','dc_language','dc_relation','dc_coverage','dc_rights');
    /// Launch drop field for each item
        foreach($fields as $f){
            $field = new XMLDBField($f);
            $result = $result && drop_field($table, $field);
        }
    }
        return $result;
}    
?>
