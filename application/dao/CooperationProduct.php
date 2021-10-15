<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/pdoConnect.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dto/CooperationProduct.php';
class CooperationProductDAO {
	private $pdo;
	function __construct() {
		$oPdo = new pdoConnect ();
		$this->pdo = $oPdo->connectPdo ();
	}
	public function findByCooperationProductSeq($sCooperationProductSeq) {
		$sQuery = ' SELECT
                        *
                    FROM
                        tCooperationProductList
                        
                    WHERE
                        sCooperationProductSeq = :sCooperationProductSeq';

		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":sCooperationProductSeq", $sCooperationProductSeq );
		$oPdoStatement->execute ();
		$oProduct = $oPdoStatement->fetch ();
		return $oProduct;
	}
	public function save($oCooperationProduct) {
		$sQuery = ' INSERT INTO tCooperationProductList
                               	(sCooperationProductSeq,
                                 sCooperationCompanySeq,
								 nCategorySeq,
								 sName,
								 sURL,
								 nPrice,
								 nMobilePrice,
								 dtInputDate)
                     VALUES     (:sCooperationProductSeq,
                                 :sCooperationCompanySeq,
								 :nCategorySeq,
								 :sName,
								 :sURL,
								 :nPrice,
								 :nMobilePrice,
								  NOW())';
		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":sCooperationProductSeq", $oCooperationProduct->getCooperationProductSeq () );
		$oPdoStatement->bindValue ( ":sCooperationCompanySeq", $oCooperationProduct->getCooperationCompanySeq () );
		$oPdoStatement->bindValue ( ":nCategorySeq", $oCooperationProduct->getCategorySeq () );
		$oPdoStatement->bindValue ( ":sName", $oCooperationProduct->getName () );
		$oPdoStatement->bindValue ( ":sURL", $oCooperationProduct->getURL () );
		$oPdoStatement->bindValue ( ":nPrice", $oCooperationProduct->getPrice () );
		$oPdoStatement->bindValue ( ":nMobilePrice", $oCooperationProduct->getMobilePrice () );
		$oPdoStatement->execute ();
	}
	public function update($oCooperationProduct) {
		$sQuery = ' UPDATE
                        tCooperationProductList
                    SET
                        sCooperationCompanySeq = :sCooperationCompanySeq,
                        nCategorySeq = :nCategorySeq,
                        sName = :sName,
                        sURL = :sURL,
                        nPrice = :nPrice,
                        nMobilePrice = :nMobilePrice,
                        dtInputDate = NOW()
                    WHERE
                        sCooperationProductSeq = :sCooperationProductSeq';
		$oPdoStatement = $this->pdo->prepare ( $sQuery );

		$oPdoStatement->bindValue ( ":sCooperationCompanySeq", 'kkkk' );
		$oPdoStatement->bindValue ( ":nCategorySeq", $oCooperationProduct->getCategorySeq () );
		$oPdoStatement->bindValue ( ":sName", $oCooperationProduct->getName () );
		$oPdoStatement->bindValue ( ":sURL", $oCooperationProduct->getURL () );
		$oPdoStatement->bindValue ( ":nPrice", $oCooperationProduct->getPrice () );
		$oPdoStatement->bindValue ( ":nMobilePrice", $oCooperationProduct->getMobilePrice () );
		$oPdoStatement->bindValue ( ":sCooperationProductSeq", $oCooperationProduct->getCooperationProductSeq () );
		$oPdoStatement->execute ();
	}
}