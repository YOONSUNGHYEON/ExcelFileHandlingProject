<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/pdoConnect.php';
$oPdo = new pdoConnect();
$pdo = $oPdo->connectPdo();
$sQuery = ' SELECT
                        *
                    FROM
                        tStandardProductList SPL
                        INNER JOIN tCategory CG ON (SPL.nCategorySeq = CG.nCategorySeq)
                    WHERE
                        CG.nCategorySeq = :nCategorySeq LIMIT 5';

$oPdoStatement = $pdo->prepare($sQuery);
$oPdoStatement->bindValue(":nCategorySeq", 57905);

$oPdoStatement->execute();
while ($oStandardProductRow = $oPdoStatement->fetch()) {
    print_r($oStandardProductRow);
    echo "<br>";
    array_push($aStandardProduct, $oStandardProductRow);
}


