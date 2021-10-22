<?php


class CooperationProductDAO {
	public function findByCooperationProductSeq($oPDO, $sCooperationProductSeq) {
		$sQuery = ' SELECT
                        *
                    FROM
                        tCooperationProductList
                        
                    WHERE
                        sCooperationProductSeq = :sCooperationProductSeq';

		$oPDOStatement = $oPDO->prepare ( $sQuery );
		$oPDOStatement->bindValue ( ":sCooperationProductSeq", $sCooperationProductSeq );
		if (! $oPDOStatement->execute ()) {
			throw new PDOException ( '쿼리 실행 실패' );
		}
		$oProduct = $oPDOStatement->fetch ();
		return $oProduct;
	}
	public function countByCategorySeq($oPDO, $nCategorySeq) {
		$sQuery = ' SELECT
                        COUNT(sCooperationProductSeq) AS CNT
                    FROM
                        tCooperationProductList
                    WHERE
                        nCategorySeq = :nCategorySeq';
		$oPDOStatement = $oPDO->prepare ( $sQuery );
		$oPDOStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		if (! $oPDOStatement->execute ()) {
			throw new PDOException ( '쿼리 실행 실패' );
		}
		$aStandardProductRow = $oPDOStatement->fetch ();
		return $aStandardProductRow ['CNT'];
	}
	public function findByCategorySeqOrderByNameASC($oPDO, $nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       CPL.sCooperationProductSeq,
					   CPL.sName,
					   CPL.sCooperationCompanySeq,
					   CPL.sURL,
					   CPL.nPrice,
					   CPL.nMobilePrice,
					   CPL.dtInputDate,
					   CC.sName AS sCooperationCompanyName
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

		$oPDOStatement = $oPDO->prepare ( $sQuery );
		$oPDOStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPDOStatement->bindValue ( ":nLimitCount", $nLimitCount );
		$oPDOStatement->bindValue ( ":nStartCount", $nStartCount );
		if (! $oPDOStatement->execute ()) {
			throw new PDOException ( '쿼리 실행 실패' );
		}
		$aStandardProduct = array ();
		while ( $oStandardProductRow = $oPDOStatement->fetch ( PDO::FETCH_ASSOC ) ) {
			array_push ( $aStandardProduct, $oStandardProductRow );
		}
		return $aStandardProduct;
	}
	public function findByCategorySeqOrderByNameDESC($oPDO, $nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       CPL.sCooperationProductSeq,
					   CPL.sName,
					   CPL.sCooperationCompanySeq,
					   CPL.sURL,
					   CPL.nPrice,
					   CPL.nMobilePrice,
					   CPL.dtInputDate,
					   CC.sName AS sCooperationCompanyName
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

		$oPDOStatement = $oPDO->prepare ( $sQuery );
		$oPDOStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPDOStatement->bindValue ( ":nLimitCount", $nLimitCount );
		$oPDOStatement->bindValue ( ":nStartCount", $nStartCount );
		if (!$oPDOStatement->execute ()) {
			throw new PDOException ( '쿼리 실행 실패' );
		}
		$aStandardProduct = array ();
		while ( $oStandardProductRow = $oPDOStatement->fetch ( PDO::FETCH_ASSOC ) ) {
			array_push ( $aStandardProduct, $oStandardProductRow );
		}
		return $aStandardProduct;
	}
	public function findByCategorySeqOrderByInputDateASC($oPDO, $nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       CPL.sCooperationProductSeq,
					   CPL.sName,
					   CPL.sCooperationCompanySeq,
					   CPL.sURL,
					   CPL.nPrice,
					   CPL.nMobilePrice,
					   CPL.dtInputDate,
					   CC.sName AS sCooperationCompanyName
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

		$oPDOStatement = $oPDO->prepare ( $sQuery );
		$oPDOStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPDOStatement->bindValue ( ":nLimitCount", $nLimitCount );
		$oPDOStatement->bindValue ( ":nStartCount", $nStartCount );
		if (!$oPDOStatement->execute ()) {
			throw new PDOException ( '쿼리 실행 실패' );
		}
		$aStandardProduct = array ();
		while ( $oStandardProductRow = $oPDOStatement->fetch ( PDO::FETCH_ASSOC ) ) {
			array_push ( $aStandardProduct, $oStandardProductRow );
		}
		return $aStandardProduct;
	}
	public function findByCategorySeqOrderByInputDateDESC($oPDO, $nStartCount, $nLimitCount, $nCategorySeq) {
		$sQuery = ' SELECT
                       CPL.sCooperationProductSeq,
					   CPL.sName,
					   CPL.sCooperationCompanySeq,
					   CPL.sURL,
					   CPL.nPrice,
					   CPL.nMobilePrice,
					   CPL.dtInputDate,
					   CC.sName AS sCooperationCompanyName
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

		$oPDOStatement = $oPDO->prepare ( $sQuery );
		$oPDOStatement->bindValue ( ":nCategorySeq", $nCategorySeq );
		$oPDOStatement->bindValue ( ":nLimitCount", $nLimitCount );
		$oPDOStatement->bindValue ( ":nStartCount", $nStartCount );
		$oPDOStatement->execute ();

		if (!$oPDOStatement->execute ()) {
			throw new PDOException ( '쿼리 실행 실패' );
		}
		$aStandardProduct = array ();
		while ( $oStandardProductRow = $oPDOStatement->fetch ( PDO::FETCH_ASSOC ) ) {
			array_push ( $aStandardProduct, $oStandardProductRow );
		}
		return $aStandardProduct;
	}
	public function save($oPDO, $oCooperationProduct) {
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
		$oPDOStatement = $oPDO->prepare ( $sQuery );
		$oPDOStatement->bindValue ( ":sCooperationProductSeq", $oCooperationProduct->getCooperationProductSeq () );
		$oPDOStatement->bindValue ( ":sCooperationCompanySeq", $oCooperationProduct->getCooperationCompanySeq () );
		$oPDOStatement->bindValue ( ":nCategorySeq", $oCooperationProduct->getCategorySeq () );
		$oPDOStatement->bindValue ( ":sName", $oCooperationProduct->getName () );
		$oPDOStatement->bindValue ( ":sURL", $oCooperationProduct->getURL () );
		$oPDOStatement->bindValue ( ":nPrice", $oCooperationProduct->getPrice () );
		$oPDOStatement->bindValue ( ":nMobilePrice", $oCooperationProduct->getMobilePrice () );
		$oPDOStatement->bindValue ( ":dtInputDate", $oCooperationProduct->getInputDate () );
		if(!$oPDOStatement->execute ()) {
			throw new PDOException ( '쿼리 실행 실패' );
		}
		
	}
	public function update($oPDO, $oCooperationProduct) {
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
		$oPDOStatement = $oPDO->prepare ( $sQuery );

		$oPDOStatement->bindValue ( ":sCooperationCompanySeq", $oCooperationProduct->getCooperationCompanySeq () );
		$oPDOStatement->bindValue ( ":nCategorySeq", $oCooperationProduct->getCategorySeq () );
		$oPDOStatement->bindValue ( ":sName", $oCooperationProduct->getName () );
		$oPDOStatement->bindValue ( ":sURL", $oCooperationProduct->getURL () );
		$oPDOStatement->bindValue ( ":nPrice", $oCooperationProduct->getPrice () );
		$oPDOStatement->bindValue ( ":nMobilePrice", $oCooperationProduct->getMobilePrice () );
		$oPDOStatement->bindValue ( ":sCooperationProductSeq", $oCooperationProduct->getCooperationProductSeq () );
		$oPDOStatement->bindValue ( ":dtInputDate", $oCooperationProduct->getInputDate () );
		if(!$oPDOStatement->execute ()) {
			throw new PDOException ( '쿼리 실행 실패' );
		}
	}
}