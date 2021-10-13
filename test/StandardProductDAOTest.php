<?php 
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/StandardProduct.php';

$oStandardProductDAO =  new StandardProductDAO();
findListByCategorySeq($oStandardProductDAO);
//save($oStandardProductDAO);
function save($oStandardProductDAO){
	try {
		$oStandardProductDAO->save(103, 57906, '파이프밴더 CT-364A타입', 0,0,0,0);
	}catch(Exception $e){
		echo 'Message: ' .$e->getMessage();
	}
}

function findListByCategorySeq($oStandardProductDAO) {
    try {
        $aStandardProduct = $oStandardProductDAO->findListByCategorySeq(57905);       
        print_r(json_encode($aStandardProduct, JSON_PRETTY_PRINT));
    }catch(Exception $e){
        echo 'Message: ' .$e->getMessage();
    }
}
