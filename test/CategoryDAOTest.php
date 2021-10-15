<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/Category.php';

$oCategory = new CategoryDAO ();
findAll ( $oCategory );
function save($oCategory) {
	try {
		$oCategory->save ( 103, "test" );
	} catch ( Exception $e ) {
		echo 'Message: ' . $e->getMessage ();
	}
}
function findAll($oCategory) {
	try {
		$aCategory = $oCategory->findAll ();
		print_r ( $aCategory [5] ['sName'] );
	} catch ( Exception $e ) {
		echo 'Message: ' . $e->getMessage ();
	}
}
