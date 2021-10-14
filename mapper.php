<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/controller/Output.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/controller/Input.php';
$oInputController = new InputController();
$oOutputController = new OutputController();
if ('uploadFile' == $_GET['method']) {
    echo $oInputController->upload();
} else if ('findCategoryList' == $_GET['method']) {
    echo $oOutputController->findCategoryList();
} else if ('findListByCategorySeq' == $_GET['method']) {
    echo $oOutputController->findListByCategorySeq();
} else if ('findStandardProductList' == $_GET['method']) {
	echo $oOutputController->findStandardProductList();
}
