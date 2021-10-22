<?php

class StandardProductDAO {

	// tStandardProductList에 StandardProductSeq와 같은 row 찾기
	public function findByStandardProductSeq($oPDO, $nStandardProductSeq) {
		$sQuery = ' SELECT
                        *
                    FROM
                        tStandardProductList SPL
                        RIGHT OUTER JOIN tCategory CG
                        ON (SPL.nCategorySeq = CG.nCategorySeq )
                    WHERE
                        SPL.nStandardProductSeq = :nStandardProductSeq';

		$oPDOStatement = $oPDO->prepare ( $sQuery );
		$oPDOStatement->bindValue ( ":nStandardProductSeq", $nStandardProductSeq );
		if ($oPDOStatement->execute ()) {
			$oProduct = $oPDOStatement->fetch ();
			return $oProduct;
		}
		return false;
	}

	/**
	 * 
	 * @param unknown $oPDO
	 * @param unknown $nStartCount
	 * @param unknown $nLimitCount
	 * @param unknown $nCategorySeq
	 * @param unknown $aSortPriority
	 * @return array|boolean
	 */
	public function findByCategorySeqOrderByASC($oPDO, $nStartCount, $nLimitCount, $nCategorySeq, $aSortPriority){
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
		$oPDOStatement = $oPDO->prepare ($sQuery);
		$oPDOStatement->bindValue (':nCategorySeq', $nCategorySeq);
		$oPDOStatement->bindValue (':nLimitCount', $nLimitCount);
		$oPDOStatement->bindValue (':nStartCount', $nStartCount);
		$aStandardProduct = array ();
		if ($oPDOStatement->execute ()) {
			while ( $oStandardProductRow = $oPDOStatement->fetch ( PDO::FETCH_ASSOC ) ) {
				array_push ( $aStandardProduct, $oStandardProductRow );
			}
			return $aStandardProduct;
		}
		return false;
	}
	
	
	public function findByCategorySeqOrderByDESC($oPDO,$nStartCount, $nLimitCount, $nCategorySeq, $aSortPriority){
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
		$oPDOStatement = $oPDO->prepare ( $sQuery );
		$oPDOStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPDOStatement->bindValue ( ":nLimitCount", $nLimitCount );
		$oPDOStatement->bindValue ( ":nStartCount", $nStartCount );
		$aStandardProduct = array ();
		if ($oPDOStatement->execute ()) {
			while ( $oStandardProductRow = $oPDOStatement->fetch ( PDO::FETCH_ASSOC ) ) {
				array_push ( $aStandardProduct, $oStandardProductRow );
			}
			return $aStandardProduct;
		}
		return false;
	}
	
	
	public function save($oPDO, $nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount) {
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
		$oPDOStatement = $oPDO->prepare ( $sQuery );
		$oPDOStatement->bindValue ( ":nStandardProductSeq", $nStandardProductSeq );
		$oPDOStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPDOStatement->bindValue ( ":sName", $sName );
		$oPDOStatement->bindValue ( ":nLowestPrice", $nLowestPrice );
		$oPDOStatement->bindValue ( ":nMobileLowestPrice", $nMobileLowestPrice );
		$oPDOStatement->bindValue ( ":nAveragePrice", $nAveragePrice );
		$oPDOStatement->bindValue ( ":nCooperationCompayCount", $nCooperationCompayCount );
		return $oPDOStatement->execute ();
	}
	public function update($oPDO, $nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount) {
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
		$oPDOStatement = $oPDO->prepare ( $sQuery );
		$oPDOStatement->bindValue ( ":nStandardProductSeq", $nStandardProductSeq );
		$oPDOStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPDOStatement->bindValue ( ":sName", $sName );
		$oPDOStatement->bindValue ( ":nLowestPrice", $nLowestPrice );
		$oPDOStatement->bindValue ( ":nMobileLowestPrice", $nMobileLowestPrice );
		$oPDOStatement->bindValue ( ":nAveragePrice", $nAveragePrice );
		$oPDOStatement->bindValue ( ":nCooperationCompayCount", $nCooperationCompayCount );
		$oPDOStatement->execute ();
	}
	public function countByCategorySeq($oPDO, $nCategorySeq) {
		$sQuery = ' SELECT
                        COUNT(*) AS CNT
                    FROM
                        tStandardProductList
                    WHERE
                        nCategorySeq = :nCategorySeq';
		$oPDOStatement = $oPDO->prepare ( $sQuery );
		$oPDOStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		if($oPDOStatement->execute ()) {
			$aStandardProductRow = $oPDOStatement->fetch();
			return $aStandardProductRow ['CNT'];
		}
		return false;
	}
}