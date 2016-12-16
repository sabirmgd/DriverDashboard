<?php

/*
 * Editor server script for DB table prices
 * Created by http://editor.datatables.net/generator
 */

// DataTables PHP library and database connection
include( "lib/DataTables.php" );

// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate;

// The following statement can be removed after the first run (i.e. the database
// table has been created). It is a good idea to do this to help improve
// performance.
$db->sql( "CREATE TABLE IF NOT EXISTS `prices` (
	`ID` int(10) NOT NULL auto_increment,
	`perkm` numeric(9,2),
	`permin` numeric(9,2),
	`starttime` time,
	`endtime` time,
	PRIMARY KEY( `ID` )
);" );

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'prices', 'ID' )
	->fields(
		Field::inst( 'perkm' ),
		Field::inst( 'permin' ),
		Field::inst( 'starttime' )
			->validator( 'Validate::dateFormat', array( 'format'=>'g:i a' ) )
			->getFormatter( 'Format::datetime', array( 'from'=>'H:i:s', 'to'  =>'g:i a' ) )
			->setFormatter( 'Format::datetime', array( 'to'  =>'H:i:s', 'from'=>'g:i a' ) ),
		Field::inst( 'endtime' )
			->validator( 'Validate::dateFormat', array( 'format'=>'g:i a' ) )
			->getFormatter( 'Format::datetime', array( 'from'=>'H:i:s', 'to'  =>'g:i a' ) )
			->setFormatter( 'Format::datetime', array( 'to'  =>'H:i:s', 'from'=>'g:i a' ) )
	)
	->process( $_POST )
	->json();
