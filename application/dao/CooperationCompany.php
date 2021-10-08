<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/pdoConnect.php';
class CooperationCompanyDAO {
	private $pdo;
	function __construct() {
		$oPdo = new pdoConnect ();
		$this->pdo = $oPdo->connectPdo ();
	}

	// 협력사 추가
	public function save($nCooperationCompanySeq, $sName) {
		$sQuery = ' INSERT INTO tCooperationCompany
                               	(nCooperationCompanySeq,
                                 sName)
                     VALUES     (:nCooperationCompanySeq,
                                 :sName)';
		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nCooperationCompanySeq", $nCooperationCompanySeq );
		$oPdoStatement->bindValue ( ":sName", $sName );
		$oPdoStatement->execute ();
	}
	
	// 게시판 옵션 아이디에 따른 게시판 목록
	public function findByCooperationCompanySeq($nCooperationCompanySeq) {
		$sQuery = ' SELECT
                        *
                    FROM
                        tCooperationCompany 
                    WHERE
                        nCooperationCompanySeq = :nCooperationCompanySeqs';
		
		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nBoardOptionId", $nOptionId );
		$oPdoStatement->execute ();
		$aBoardList = array ();
		while ( $aBoardRow = $oPdoStatement->fetch ( PDO::FETCH_ASSOC ) ) {
			$aBoardList [] = $aBoardRow;
		}
		return $aBoardList;
	}
}