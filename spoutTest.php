<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/StandardProduct.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/Category.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/src/Spout/Autoloader/autoload.php';
use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

if (! empty ( $_FILES ['file'] ['name'] )) {
	$oReader = ReaderEntityFactory::createReaderFromFile ( Type::XLSX );
	$oReader->open ( $_FILES ['file'] ['tmp_name'] );
	$pathinfo = pathinfo ( $_FILES ["file"] ["name"] );
	$oStandardProductDAO = new StandardProductDAO ();
	$oCategory =  new CategoryDAO();
	if (($pathinfo ['extension'] == 'xlsx' || $pathinfo ['extension'] == 'xls') && $_FILES ['file'] ['size'] > 0) {
	} else {
		echo "PLEASE SELECT A VALID EXCEL FILE";
	}
	foreach ( $oReader->getSheetIterator () as $oSheet ) {
		$count = 1;
		foreach ( $oSheet->getRowIterator () as $oRow ) {
			if ($count > 1) {
				/*$aValue = $oRow->toArray ();
				$nCategorySeq = $aValue [0];
				$sName = $aValue [1];
				try {
					$oCategory->save($nCategorySeq, $sName);
				}catch(Exception $e){
					echo 'Message: ' .$e->getMessage();
				}*/
				$aValue = $oRow->toArray ();
				$nStandardProductSeq = $aValue [0];
				$nCategorySeq = $aValue [1];
				$sName = $aValue [2];
				$nLowestPrice = $aValue [3];
				$nMobileLowestPrice = $aValue [4];
				$nAveragePrice = $aValue [5];
				$nCooperationCompayCount = $aValue [6];
				print_r($aValue);
				try {
					$oStandardProductDAO->save ( $nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount);
				} catch ( Exception $e ) {
					echo 'Message: ' . $e->getMessage ();
				}
				echo "<br/>";
				echo "<br/>";
				echo "<br/>";
				echo "<br/>";
			}
			$count ++;
		}
		echo "successfully uploaded!";
	}

	$oReader->close ();
}else {
	echo "UPLOAD AN EXCEL FILE";
}


?>