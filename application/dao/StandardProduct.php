<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/pdoConnect.php';

class StandardProductDAO {
	private $pdo;
	function __construct() {
		$oPdo = new pdoConnect ();
		$this->pdo = $oPdo->connectPdo ();
	}

	// 카테고리 추가
	public function save($nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount) {
		$sQuery = ' INSERT INTO tStandardProductList
                               	(nStandardProductSeq,
								 nCategorySeq,
								 sName,
								 nLowestPrice,
								 nMobileLowestPrice,
								 nAveragePrice,
 								 nCooperationCompayCount)
                     VALUES     (:nStandardProductSeq,
                                 :nCategorySeq,
								 :sName,
								 :nLowestPrice,
								 :nMobileLowestPrice,
								 :nAveragePrice,
								 :nCooperationCompayCount)';
		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nStandardProductSeq", $nStandardProductSeq);
		$oPdoStatement->bindValue ( ":nCategorySeq", $nCategorySeq);
		$oPdoStatement->bindValue ( ":sName", $sName);
		$oPdoStatement->bindValue ( ":nLowestPrice", $nLowestPrice);
		$oPdoStatement->bindValue ( ":nMobileLowestPrice", $nMobileLowestPrice);
		$oPdoStatement->bindValue ( ":nAveragePrice", $nAveragePrice);
		$oPdoStatement->bindValue ( ":nCooperationCompayCount", $nCooperationCompayCount);
		$oPdoStatement->execute ();
	}
}