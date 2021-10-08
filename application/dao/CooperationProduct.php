<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/pdoConnect.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dto/CooperationProduct.php';
class CooperationProductDAO {
	private $pdo;
	function __construct() {
		$oPdo = new pdoConnect ();
		$this->pdo = $oPdo->connectPdo ();
	}

	// 카테고리 추가
	public function save($oCooperationProduct) {
		$sQuery = ' INSERT INTO tCooperationProductList
                               	(nCooperationProductSeq,
                                 nCooperationCompanySeq,
								 nCategorySeq,
								 sName,
								 sURL,
								 nPrice,
								 nMobilePrice,
								 dtInputDate)
                     VALUES     (:nCooperationProductSeq,
                                 :nCooperationCompanySeq,
								 :nCategorySeq,
								 :sName,
								 :sURL,
								 :nPrice,
								 :nMobilePrice,
								  NOW())';
		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nCooperationProductSeq", $oCooperationProduct->getCooperationProductSeq());
		$oPdoStatement->bindValue ( ":nCooperationCompanySeq", $oCooperationProduct->getCooperationCompanySeq());
		$oPdoStatement->bindValue ( ":nCategorySeq", $oCooperationProduct->getCategorySeq());
		$oPdoStatement->bindValue ( ":sName", $oCooperationProduct->getName() );
		$oPdoStatement->bindValue ( ":sURL", $oCooperationProduct->getURL());
		$oPdoStatement->bindValue ( ":nPrice", $oCooperationProduct->getPrice());
		$oPdoStatement->bindValue ( ":nMobilePrice", $oCooperationProduct->getMobilePrice());
		$oPdoStatement->execute ();
	}
}