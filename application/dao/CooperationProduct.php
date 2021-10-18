<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/pdoConnect.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dto/CooperationProduct.php';
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
	public function countByCategorySeq($nCategorySeq) {
	    $sQuery = ' SELECT
                        count(*)
                    FROM
                        tCooperationProductList
                    WHERE
                        nCategorySeq = :nCategorySeq';
	    $oPdoStatement = $this->pdo->prepare ( $sQuery );
	    $oPdoStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
	    $oPdoStatement->execute ();
	    $aStandardProductRow = $oPdoStatement->fetch ();
	    return $aStandardProductRow ['count(*)'];
	}
	public function findByCategorySeqOrderByNameASC($nStartCount, $nLimitCount, $nCategorySeq) {
	    $sQuery = ' SELECT
                       CPL.*, CC.sName as "sCooperationCompanyName"
                    FROM
                        tCooperationProductList CPL
                        LEFT OUTER JOIN tCooperationCompany CC 
                        ON (CPL.sCooperationCompanySeq = CC.sCooperationCompanySeq)
					WHERE
                        CPL.nCategorySeq = :nCategorySeq
                    ORDER BY
                        CPL.sName,
                        CPL.dtInputDate
					LIMIT
                        :nLimitCount
                    OFFSET :nStartCount';
	    
	    $oPdoStatement = $this->pdo->prepare ( $sQuery );
	    $oPdoStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
	    $oPdoStatement->bindValue ( ":nLimitCount", $nLimitCount );
	    $oPdoStatement->bindValue ( ":nStartCount", $nStartCount );
	    $oPdoStatement->execute ();
	    
	    $aStandardProduct = array ();
	    while ( $oStandardProductRow = $oPdoStatement->fetch ( PDO::FETCH_ASSOC ) ) {
	        array_push ( $aStandardProduct, $oStandardProductRow );
	    }
	    return $aStandardProduct;
	}
	
	public function findByCategorySeqOrderByNameDESC($nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       CPL.*, CC.sName as "sCooperationCompanyName"
                    FROM
                        tCooperationProductList CPL
                        LEFT OUTER JOIN tCooperationCompany CC
                        ON (CPL.sCooperationCompanySeq = CC.sCooperationCompanySeq)
					WHERE
                        CPL.nCategorySeq = :nCategorySeq
                    ORDER BY
                        CPL.sName DESC,
                        CPL.dtInputDate DESC
					LIMIT
                        :nLimitCount
                    OFFSET :nStartCount';
		
		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPdoStatement->bindValue ( ":nLimitCount", $nLimitCount );
		$oPdoStatement->bindValue ( ":nStartCount", $nStartCount );
		$oPdoStatement->execute ();
		
		$aStandardProduct = array ();
		while ( $oStandardProductRow = $oPdoStatement->fetch ( PDO::FETCH_ASSOC ) ) {
			array_push ( $aStandardProduct, $oStandardProductRow );
		}
		return $aStandardProduct;
	}
	public function findByCategorySeqOrderByInputDateASC($nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       CPL.*, CC.sName as "sCooperationCompanyName"
                    FROM
                        tCooperationProductList CPL
                        LEFT OUTER JOIN tCooperationCompany CC
                        ON (CPL.sCooperationCompanySeq = CC.sCooperationCompanySeq)
					WHERE
                        CPL.nCategorySeq = :nCategorySeq
                    ORDER BY
                       	CPL.dtInputDate,
                        CPL.sName
					LIMIT
                        :nLimitCount
                    OFFSET :nStartCount';
		
		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPdoStatement->bindValue ( ":nLimitCount", $nLimitCount );
		$oPdoStatement->bindValue ( ":nStartCount", $nStartCount );
		$oPdoStatement->execute ();
		
		$aStandardProduct = array ();
		while ( $oStandardProductRow = $oPdoStatement->fetch ( PDO::FETCH_ASSOC ) ) {
			array_push ( $aStandardProduct, $oStandardProductRow );
		}
		return $aStandardProduct;
	}
	
	public function findByCategorySeqOrderByInputDateDESC($nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       CPL.*, CC.sName as "sCooperationCompanyName"
                    FROM
                        tCooperationProductList CPL
                        LEFT OUTER JOIN tCooperationCompany CC
                        ON (CPL.sCooperationCompanySeq = CC.sCooperationCompanySeq)
					WHERE
                        CPL.nCategorySeq = :nCategorySeq
                    ORDER BY
						CPL.dtInputDate DESC,
                        CPL.sName DESC
					LIMIT
                        :nLimitCount
                    OFFSET :nStartCount';
		
		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPdoStatement->bindValue ( ":nLimitCount", $nLimitCount );
		$oPdoStatement->bindValue ( ":nStartCount", $nStartCount );
		$oPdoStatement->execute ();
		
		$aStandardProduct = array ();
		while ( $oStandardProductRow = $oPdoStatement->fetch ( PDO::FETCH_ASSOC ) ) {
			array_push ( $aStandardProduct, $oStandardProductRow );
		}
		return $aStandardProduct;
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
								 :dtInputDate)';
		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":sCooperationProductSeq", $oCooperationProduct->getCooperationProductSeq () );
		$oPdoStatement->bindValue ( ":sCooperationCompanySeq", $oCooperationProduct->getCooperationCompanySeq () );
		$oPdoStatement->bindValue ( ":nCategorySeq", $oCooperationProduct->getCategorySeq () );
		$oPdoStatement->bindValue ( ":sName", $oCooperationProduct->getName () );
		$oPdoStatement->bindValue ( ":sURL", $oCooperationProduct->getURL () );
		$oPdoStatement->bindValue ( ":nPrice", $oCooperationProduct->getPrice () );
		$oPdoStatement->bindValue ( ":nMobilePrice", $oCooperationProduct->getMobilePrice () );
		$oPdoStatement->bindValue ( ":dtInputDate", $oCooperationProduct->getInputDate () );
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
                        dtInputDate = :dtInputDate
                    WHERE
                        sCooperationProductSeq = :sCooperationProductSeq';
		$oPdoStatement = $this->pdo->prepare ( $sQuery );

		$oPdoStatement->bindValue ( ":sCooperationCompanySeq", $oCooperationProduct->getCooperationCompanySeq() );
		$oPdoStatement->bindValue ( ":nCategorySeq", $oCooperationProduct->getCategorySeq () );
		$oPdoStatement->bindValue ( ":sName", $oCooperationProduct->getName () );
		$oPdoStatement->bindValue ( ":sURL", $oCooperationProduct->getURL () );
		$oPdoStatement->bindValue ( ":nPrice", $oCooperationProduct->getPrice () );
		$oPdoStatement->bindValue ( ":nMobilePrice", $oCooperationProduct->getMobilePrice () );
		$oPdoStatement->bindValue ( ":sCooperationProductSeq", $oCooperationProduct->getCooperationProductSeq () );
		$oPdoStatement->bindValue ( ":dtInputDate", $oCooperationProduct->getInputDate () );
		$oPdoStatement->execute ();
	}
}