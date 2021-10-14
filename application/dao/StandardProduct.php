<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/pdoConnect.php';

class StandardProductDAO
{

    private $pdo;

    function __construct()
    {
        $oPdo = new pdoConnect();
        $this->pdo = $oPdo->connectPdo();
    }
    
    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }
    
    public function rollBack()
    {
        $this->pdo->rollBack();
    }
    
    public function commit()
    {
        $this->pdo->commit();
    }
    
    
    public function findByStandardProductSeq($nStandardProductSeq)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        tStandardProductList SPL
                        RIGHT OUTER JOIN tCategory CG
                        ON (SPL.nCategorySeq = CG.nCategorySeq )
                    WHERE
                        SPL.nStandardProductSeq = :nStandardProductSeq';

        $oPdoStatement = $this->pdo->prepare($sQuery);
        $oPdoStatement->bindValue(":nStandardProductSeq", $nStandardProductSeq);
        $oPdoStatement->execute();
        $oProduct = $oPdoStatement->fetch();
        return $oProduct;
    }
    
    public function findListByCategorySeq($nCategorySeq) {
        $sQuery = ' SELECT
                       SPL.*, CG.sName as "sCategoryName"
                    FROM
                        tStandardProductList SPL
                        LEFT OUTER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
                    WHERE
                        CG.nCategorySeq = :nCategorySeq
                     ORDER BY 
                        SPL.nStandardProductSeq DESC';
        
        $oPdoStatement = $this->pdo->prepare($sQuery);
        $oPdoStatement->bindValue(":nCategorySeq", $nCategorySeq);
        $oPdoStatement->execute();
        
        $aStandardProduct = array();
        while ($oStandardProductRow = $oPdoStatement->fetch(PDO::FETCH_ASSOC)) {
            array_push($aStandardProduct, $oStandardProductRow);
        }
        return $aStandardProduct;
    }
    
    public function findAll($nStartCount, $nLimitCount) {
    	$sQuery = ' SELECT
                       SPL.*, CG.sName as "sCategoryName"
                    FROM
                        tStandardProductList SPL
                        LEFT OUTER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
                    ORDER BY
                        SPL.nStandardProductSeq DESC
					LIMIT 
                        :nLimitCount 
                    OFFSET :nStartCount';
    	
    	$oPdoStatement = $this->pdo->prepare($sQuery);
    	$oPdoStatement->bindValue ( ":nLimitCount", $nLimitCount );
    	$oPdoStatement->bindValue ( ":nStartCount", $nStartCount );
    	$oPdoStatement->execute();
    	
    	$aStandardProduct = array();
    	while ($oStandardProductRow = $oPdoStatement->fetch(PDO::FETCH_ASSOC)) {
    		array_push($aStandardProduct, $oStandardProductRow);
    	}
    	return $aStandardProduct;
    }

    public function save($nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount)
    {
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
        $oPdoStatement = $this->pdo->prepare($sQuery);
        $oPdoStatement->bindValue(":nStandardProductSeq", $nStandardProductSeq);
        $oPdoStatement->bindValue(":nCategorySeq", $nCategorySeq);
        $oPdoStatement->bindValue(":sName", $sName);
        $oPdoStatement->bindValue(":nLowestPrice", $nLowestPrice);
        $oPdoStatement->bindValue(":nMobileLowestPrice", $nMobileLowestPrice);
        $oPdoStatement->bindValue(":nAveragePrice", $nAveragePrice);
        $oPdoStatement->bindValue(":nCooperationCompayCount", $nCooperationCompayCount);
        $oPdoStatement->execute();
    }

    public function update($nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount)
    {
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
                        nStandardProductSeq = :nStandardProductSeq'
        ;
        $oPdoStatement = $this->pdo->prepare($sQuery);
        $oPdoStatement->bindValue(":nStandardProductSeq", $nStandardProductSeq);
        $oPdoStatement->bindValue(":nCategorySeq", $nCategorySeq);
        $oPdoStatement->bindValue(":sName", $sName);
        $oPdoStatement->bindValue(":nLowestPrice", $nLowestPrice);
        $oPdoStatement->bindValue(":nMobileLowestPrice", $nMobileLowestPrice);
        $oPdoStatement->bindValue(":nAveragePrice", $nAveragePrice);
        $oPdoStatement->bindValue(":nCooperationCompayCount", $nCooperationCompayCount);
        $oPdoStatement->execute();
    }
}