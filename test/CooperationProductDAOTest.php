<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/CooperationCompany.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/CooperationProduct.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dto/CooperationProduct.php';
$oCooperationProductDAO = new CooperationProductDAO();

save ( $oCooperationProductDAO);
function save($oCooperationProductDAO) {
	try {
		$oCooperationProduct = new CooperationProduct ( '1', '1', 1, 'test', 'test', 0, 0 );
		$oCooperationProductDAO->save($oCooperationProduct);
	} catch ( Exception $e ) {
		echo 'Message: ' . $e->getMessage ();
	}
}
function findByCooperationProductSeq($oCooperationProductDAO) {
    try {
        $oCooperationProductDAO->findByCooperationProductSeq("694387201");
    } catch ( Exception $e ) {
        echo 'Message: ' . $e->getMessage ();
    }
}