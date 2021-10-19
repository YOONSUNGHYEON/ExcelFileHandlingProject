<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/include/pdoConnect.php';
class CooperationCompanyDAO {
	private $pdo;
	function __construct() {
		$oPdo = new pdoConnect ();
		$this->pdo = $oPdo->connectPdo ();
	}

	// 협력사 추가
	public function save($sCooperationCompanySeq, $sName) {
		$sQuery = ' INSERT INTO tCooperationCompany
                               	(sCooperationCompanySeq,
                                 sName)
                     VALUES     (:sCooperationCompanySeq,
                                 :sName)';
		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":sCooperationCompanySeq", $sCooperationCompanySeq );
		$oPdoStatement->bindValue ( ":sName", $sName );
		$oPdoStatement->execute ();
	}
	
	public function findByCooperationCompanySeq($sCooperationCompanySeq) {
		$sQuery = ' SELECT
                        *
                    FROM
                        tCooperationCompany 
                    WHERE
                        sCooperationCompanySeq = :sCooperationCompanySeqs';
		
		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":sCooperationCompanySeqs", $sCooperationCompanySeq );
		if ($oPdoStatement->execute()) {
			$oCompany = $oPdoStatement->fetch();
			return $oCompany;
		}
		return false;
	}
	
	public function update($sCooperationCompanySeq, $sName) {
	    $sQuery = ' UPDATE
                        tCooperationCompany
                    SET
                        sName = :sName
                    WHERE
                        sCooperationCompanySeq = :sCooperationCompanySeq';
	    $oPdoStatement = $this->pdo->prepare ( $sQuery );
	    
	    $oPdoStatement->bindValue ( ":sCooperationCompanySeq", $sCooperationCompanySeq );
	    $oPdoStatement->bindValue ( ":sName", $sName );
	    $oPdoStatement->execute();
	}
}