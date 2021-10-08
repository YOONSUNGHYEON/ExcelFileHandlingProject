<?php 
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/Category.php';

$oCategory =  new CategoryDAO();


function save($oCategory){
	try {
		$oCategory->save(103, "test");
	}catch(Exception $e){
		echo 'Message: ' .$e->getMessage();
	}
}
