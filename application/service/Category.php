<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/Category.php';
class CategoryService {
	private $oCategoryDAO;
	function __construct() {
		$this->oCategoryDAO = new CategoryDAO ();
	}
	public function findAll() {
		$aCategory = $this->oCategoryDAO->findAll ();
		return $aCategory;
	}
}