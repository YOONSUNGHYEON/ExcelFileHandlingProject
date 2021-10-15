<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/pdoConnect.php';
class StandardProductDAO {
	private $pdo;
	function __construct() {
		$oPdo = new pdoConnect ();
		$this->pdo = $oPdo->connectPdo ();
	}
	public function beginTransaction() {
		$this->pdo->beginTransaction ();
	}
	public function rollBack() {
		$this->pdo->rollBack ();
	}
	public function commit() {
		$this->pdo->commit ();
	}

	// tStandardProductList에 StandardProductSeq와 같은 row 찾기
	public function findByStandardProductSeq($nStandardProductSeq) {
		$sQuery = ' SELECT
                        *
                    FROM
                        tStandardProductList SPL
                        RIGHT OUTER JOIN tCategory CG
                        ON (SPL.nCategorySeq = CG.nCategorySeq )
                    WHERE
                        SPL.nStandardProductSeq = :nStandardProductSeq';

		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nStandardProductSeq", $nStandardProductSeq );
		$oPdoStatement->execute ();
		$oProduct = $oPdoStatement->fetch ();
		return $oProduct;
	}

	// CategorySeq에 해당하는 list를 StandardProductSeq로 내림차순 정렬후 가져오기
	public function findByCategorySeqOrderBySeqDESC($nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       SPL.*, CG.sName as "sCategoryName"
                    FROM
                        tStandardProductList SPL
                        LEFT OUTER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
					WHERE
                        CG.nCategorySeq = :nCategorySeq
                    ORDER BY
                        SPL.nStandardProductSeq DESC,
						SPL.sName DESC, 
						SPL.nLowestPrice DESC, 
						SPL.nMobileLowestPrice DESC, 
						SPL.nCooperationCompayCount DESC
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

	// CategorySeq에 해당하는 list를 StandardProductSeq로 오름차순 정렬후 가져오기
	public function findByCategorySeqOrderBySeqASC($nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       SPL.*, CG.sName as "sCategoryName"
                    FROM
                        tStandardProductList SPL
                        LEFT OUTER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
					WHERE
                        CG.nCategorySeq = :nCategorySeq
                    ORDER BY
                        SPL.nStandardProductSeq,
						SPL.sName,
						SPL.nLowestPrice,
						SPL.nMobileLowestPrice,
						SPL.nCooperationCompayCount
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

	// CategorySeq에 해당하는 list를 CooperationCompayCount로 내림차순 정렬후 가져오기
	public function findByCategorySeqOrderByCooperationCompayCountDESC($nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       SPL.*, CG.sName as "sCategoryName"
                    FROM
                        tStandardProductList SPL
                        LEFT OUTER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
					WHERE
                        CG.nCategorySeq = :nCategorySeq
                    ORDER BY
						SPL.nCooperationCompayCount DESC
                        SPL.nStandardProductSeq DESC,
						SPL.sName DESC,
						SPL.nLowestPrice DESC,
						SPL.nMobileLowestPrice DESC,			
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

	// CategorySeq에 해당하는 list를 CooperationCompayCount로 오름차순 정렬후 가져오기
	public function findByCategorySeqOrderByCooperationCompayCountASC($nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       SPL.*, CG.sName as "sCategoryName"
                    FROM
                        tStandardProductList SPL
                        LEFT OUTER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
					WHERE
                        CG.nCategorySeq = :nCategorySeq
                    ORDER BY
						SPL.nCooperationCompayCount
                        SPL.nStandardProductSeq,
						SPL.sName,
						SPL.nLowestPrice,
						SPL.nMobileLowestPrice,
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
	
	// CategorySeq에 해당하는 list를 MobileLowestPrice로 내림차순 정렬후 가져오기
	public function findByCategorySeqOrderByMobileLowestPriceDESC($nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       SPL.*, CG.sName as "sCategoryName"
                    FROM
                        tStandardProductList SPL
                        LEFT OUTER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
					WHERE
                        CG.nCategorySeq = :nCategorySeq
                    ORDER BY
						SPL.nMobileLowestPrice DESC,                        
						SPL.nStandardProductSeq DESC,
						SPL.sName DESC,
						SPL.nLowestPrice DESC,						
						SPL.nCooperationCompayCount DESC
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
	
	// CategorySeq에 해당하는 list를 MobileLowestPrice로 오름차순 정렬후 가져오기
	public function findByCategorySeqOrderByMobileLowestPriceASC($nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       SPL.*, CG.sName as "sCategoryName"
                    FROM
                        tStandardProductList SPL
                        LEFT OUTER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
					WHERE
                        CG.nCategorySeq = :nCategorySeq
                    ORDER BY
						SPL.nMobileLowestPrice,
						SPL.nStandardProductSeq,
						SPL.sName,
						SPL.nLowestPrice,
						SPL.nCooperationCompayCount
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
	
	// CategorySeq에 해당하는 list를 Name로 내림차순 정렬후 가져오기
	public function findByCategorySeqOrderByNameDESC($nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       SPL.*, CG.sName as "sCategoryName"
                    FROM
                        tStandardProductList SPL
                        LEFT OUTER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
					WHERE
                        CG.nCategorySeq = :nCategorySeq
                    ORDER BY
						SPL.sName DESC,
                        SPL.nStandardProductSeq DESC,					
						SPL.nLowestPrice DESC,
						SPL.nMobileLowestPrice DESC,
						SPL.nCooperationCompayCount DESC
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
	
	// CategorySeq에 해당하는 list를 Name로 오름차순 정렬후 가져오기
	public function findByCategorySeqOrderByNameASC($nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       SPL.*, CG.sName as "sCategoryName"
                    FROM
                        tStandardProductList SPL
                        LEFT OUTER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
					WHERE
                        CG.nCategorySeq = :nCategorySeq
                    ORDER BY
						SPL.sName,
                        SPL.nStandardProductSeq,
						SPL.nLowestPrice,
						SPL.nMobileLowestPrice,
						SPL.nCooperationCompayCount
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
	
	// CategorySeq에 해당하는 list를 LowestPrice로 내림차순 정렬후 가져오기
	public function findByCategorySeqOrderByLowestPriceDESC($nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       SPL.*, CG.sName as "sCategoryName"
                    FROM
                        tStandardProductList SPL
                        LEFT OUTER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
					WHERE
                        CG.nCategorySeq = :nCategorySeq
                    ORDER BY
						SPL.nLowestPrice DESC,						
                        SPL.nStandardProductSeq DESC,
						SPL.sName DESC,
						SPL.nMobileLowestPrice DESC,
						SPL.nCooperationCompayCount DESC
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
	
	// CategorySeq에 해당하는 list를 LowestPrice로 오름차순 정렬후 가져오기
	public function findByCategorySeqOrderByLowestPriceASC($nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       SPL.*, CG.sName as "sCategoryName"
                    FROM
                        tStandardProductList SPL
                        LEFT OUTER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
					WHERE
                        CG.nCategorySeq = :nCategorySeq
                    ORDER BY
						SPL.nLowestPrice,
                        SPL.nStandardProductSeq,
						SPL.sName,
						SPL.nMobileLowestPrice,
						SPL.nCooperationCompayCount
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
		$oPdoStatement->bindValue ( ":nStandardProductSeq", $nStandardProductSeq );
		$oPdoStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPdoStatement->bindValue ( ":sName", $sName );
		$oPdoStatement->bindValue ( ":nLowestPrice", $nLowestPrice );
		$oPdoStatement->bindValue ( ":nMobileLowestPrice", $nMobileLowestPrice );
		$oPdoStatement->bindValue ( ":nAveragePrice", $nAveragePrice );
		$oPdoStatement->bindValue ( ":nCooperationCompayCount", $nCooperationCompayCount );
		$oPdoStatement->execute ();
	}
	public function update($nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount) {
		$sQuery = ' UPDATE
                        tStandardProductList
                    SET
                        nCategorySeq=:nCategorySeq,
					    sName=:sName,
						nLowestPrice=:nLowestPrice,
						nMobileLowestPrice= :nMobileLowestPrice,
						nAveragePrice = :nAveragePrice,
						nCooperationCompayCount =:nCooperationCompayCount
                    WHERE
                        nStandardProductSeq = :nStandardProductSeq';
		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nStandardProductSeq", $nStandardProductSeq );
		$oPdoStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPdoStatement->bindValue ( ":sName", $sName );
		$oPdoStatement->bindValue ( ":nLowestPrice", $nLowestPrice );
		$oPdoStatement->bindValue ( ":nMobileLowestPrice", $nMobileLowestPrice );
		$oPdoStatement->bindValue ( ":nAveragePrice", $nAveragePrice );
		$oPdoStatement->bindValue ( ":nCooperationCompayCount", $nCooperationCompayCount );
		$oPdoStatement->execute ();
	}
	public function countAll() {
		$sQuery = ' SELECT
                        count(*)
                    FROM
                        tStandardProductList';
		$oPdoStatement = $this->pdo->prepare ( $sQuery );
		$oPdoStatement->execute ();
		$aStandardProductRow = $oPdoStatement->fetch ();
		return $aStandardProductRow ['count(*)'];
	}
}