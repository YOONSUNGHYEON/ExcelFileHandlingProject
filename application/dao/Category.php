<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/include/pdoConnect.php';
class CategoryDAO {
	private $pdo;
	function __construct() {
		$oPdo = new pdoConnect ();
		$this->pdo = $oPdo->connectPdo ();
	}
	public function findAll() {
		$sQuery = ' SELECT
						nCategorySeq,
                        sName
                    FROM
                        tCategory';

		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$aCategory = array ();
		if ($oPdoStatement->execute ()) {
			while ( $oCategoryRow = $oPdoStatement->fetch ( PDO::FETCH_ASSOC ) ) {
				$aCategory [] = $oCategoryRow;
			}
			return $aCategory;
		}
		return false;
	}
}