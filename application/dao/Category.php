<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/pdoConnect.php';
class CategoryDAO {
	private $pdo;
	function __construct() {
		$oPdo = new pdoConnect ();
		$this->pdo = $oPdo->connectPdo ();
	}

	// 카테고리 추가
	public function save($nCategorySeq, $sName) {
		$sQuery = ' INSERT INTO tCategory
                               	(nCategorySeq,
                                 sName)
                     VALUES     (:nCategorySeq,
                                 :sName)';
		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPdoStatement->bindValue ( ":sName", $sName );
		$oPdoStatement->execute ();
	}
}