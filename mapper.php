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
    echo $oOutputController->findStandardProductListByCategorySeq();
} else if ('findCooperationProductListByCategorySeq' == $_GET['method']) {
    echo $oOutputController->findCooperationProductListByCategorySeq();
} else if ('download' == $_GET['method']) {
    echo $oOutputController->download();
}else if ('findStandardProductListByCategorySeq' == $_GET ['method']) {
    echo $oOutputController->findStandardProductListByCategorySeq ();
}
