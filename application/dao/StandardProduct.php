<?php

class StandardProductDAO {

	// tStandardProductList에 StandardProductSeq와 같은 row 찾기
	public function findByStandardProductSeq($oPdo, $nStandardProductSeq) {
		$sQuery = ' SELECT
                        *
                    FROM
                        tStandardProductList SPL
                        RIGHT OUTER JOIN tCategory CG
                        ON (SPL.nCategorySeq = CG.nCategorySeq )
                    WHERE
                        SPL.nStandardProductSeq = :nStandardProductSeq';

		$oPdoStatement = $oPdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nStandardProductSeq", $nStandardProductSeq );
		if ($oPdoStatement->execute ()) {
			$oProduct = $oPdoStatement->fetch ();
			return $oProduct;
		}
		return false;
	}

	/**
	 * 
	 * @param unknown $oPdo
	 * @param unknown $nStartCount
	 * @param unknown $nLimitCount
	 * @param unknown $nCategorySeq
	 * @param unknown $aSortPriority
	 * @return array|boolean
	 */
	public function findByCategorySeqOrderByASC($oPdo, $nStartCount, $nLimitCount, $nCategorySeq, $aSortPriority){
		$sQuery = ' SELECT
                       	SPL.*,
						CG.sName as sCategoryName
                    FROM
                        tStandardProductList SPL
                        LEFT OUTER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
					WHERE
                        CG.nCategorySeq = :nCategorySeq
					ORDER BY ';
		foreach ( $aSortPriority as $sSortPriority ) {
			$sQuery = $sQuery . 'SPL.' . $sSortPriority . ' ,';
		}
		$sQuery = rtrim($sQuery, ',') . 
					' LIMIT
                        :nLimitCount
                    OFFSET :nStartCount';
		$oPdoStatement = $oPdo->prepare ($sQuery);
		$oPdoStatement->bindValue (':nCategorySeq', $nCategorySeq);
		$oPdoStatement->bindValue (':nLimitCount', $nLimitCount);
		$oPdoStatement->bindValue (':nStartCount', $nStartCount);
		$aStandardProduct = array ();
		if ($oPdoStatement->execute ()) {
			while ( $oStandardProductRow = $oPdoStatement->fetch ( PDO::FETCH_ASSOC ) ) {
				array_push ( $aStandardProduct, $oStandardProductRow );
			}
			return $aStandardProduct;
		}
		return false;
	}
	
	
	public function findByCategorySeqOrderByDESC($oPdo,$nStartCount, $nLimitCount, $nCategorySeq, $aSortPriority){
		$sQuery = ' SELECT
                       	SPL.*,
						CG.sName as sCategoryName
                    FROM
                        tStandardProductList SPL
                        LEFT OUTER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
					WHERE
                        CG.nCategorySeq = :nCategorySeq
					ORDER BY ';
		foreach ( $aSortPriority as $sSortPriority ) {
			$sQuery = $sQuery . 'SPL.' . $sSortPriority . ' DESC,';
		}
		$sQuery = rtrim($sQuery, ',') .
		' LIMIT
                        :nLimitCount
                    OFFSET :nStartCount';
		$oPdoStatement = $oPdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPdoStatement->bindValue ( ":nLimitCount", $nLimitCount );
		$oPdoStatement->bindValue ( ":nStartCount", $nStartCount );
		$aStandardProduct = array ();
		if ($oPdoStatement->execute ()) {
			while ( $oStandardProductRow = $oPdoStatement->fetch ( PDO::FETCH_ASSOC ) ) {
				array_push ( $aStandardProduct, $oStandardProductRow );
			}
			return $aStandardProduct;
		}
		return false;
	}
	
	
	public function save($oPdo, $nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount) {
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
		$oPdoStatement = $oPdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nStandardProductSeq", $nStandardProductSeq );
		$oPdoStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPdoStatement->bindValue ( ":sName", $sName );
		$oPdoStatement->bindValue ( ":nLowestPrice", $nLowestPrice );
		$oPdoStatement->bindValue ( ":nMobileLowestPrice", $nMobileLowestPrice );
		$oPdoStatement->bindValue ( ":nAveragePrice", $nAveragePrice );
		$oPdoStatement->bindValue ( ":nCooperationCompayCount", $nCooperationCompayCount );
		return $oPdoStatement->execute ();
	}
	public function update($oPdo, $nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount) {
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
		$oPdoStatement = $oPdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nStandardProductSeq", $nStandardProductSeq );
		$oPdoStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPdoStatement->bindValue ( ":sName", $sName );
		$oPdoStatement->bindValue ( ":nLowestPrice", $nLowestPrice );
		$oPdoStatement->bindValue ( ":nMobileLowestPrice", $nMobileLowestPrice );
		$oPdoStatement->bindValue ( ":nAveragePrice", $nAveragePrice );
		$oPdoStatement->bindValue ( ":nCooperationCompayCount", $nCooperationCompayCount );
		$oPdoStatement->execute ();
	}
	public function countByCategorySeq($oPdo, $nCategorySeq) {
		$sQuery = ' SELECT
                        COUNT(*) AS CNT
                    FROM
                        tStandardProductList
                    WHERE
                        nCategorySeq = :nCategorySeq';
		$oPdoStatement = $oPdo->prepare ( $sQuery );
		$oPdoStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		if($oPdoStatement->execute ()) {
			$aStandardProductRow = $oPdoStatement->fetch();
			return $aStandardProductRow ['CNT'];
		}
		return false;
	}
}