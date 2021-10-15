<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dto/CooperationProduct.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/CooperationProduct.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/StandardProduct.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/Category.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/src/Spout/Autoloader/autoload.php';
use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
insertCooperationProductList ();
function test() {
	echo $_FILES ['file'] ['name'];
}
function insertCategory() {
	if (! empty ( $_FILES ['file'] ['name'] )) {
		$oReader = ReaderEntityFactory::createReaderFromFile ( Type::XLSX );
		$oReader->open ( $_FILES ['file'] ['tmp_name'] );
		$pathinfo = pathinfo ( $_FILES ["file"] ["name"] );
		$oStandardProductDAO = new StandardProductDAO ();
		$oCategory = new CategoryDAO ();
		if (($pathinfo ['extension'] == 'xlsx' || $pathinfo ['extension'] == 'xls') && $_FILES ['file'] ['size'] > 0) {
		} else {
			echo "PLEASE SELECT A VALID EXCEL FILE";
		}
		foreach ( $oReader->getSheetIterator () as $oSheet ) {
			$count = 1;
			foreach ( $oSheet->getRowIterator () as $oRow ) {
				if ($count > 1) {
					try {
						$aValue = $oRow->toArray ();
						$nCategorySeq = $aValue [0];
						$sName = $aValue [1];
						$oCategory->save ( $nCategorySeq, $sName );
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
	} else {
		echo "UPLOAD AN EXCEL FILE";
	}
}
function insertCooperationProductList() {
	if (! empty ( $_FILES ['file'] ['name'] )) {
		$aError [] = array ();
		$oReader = ReaderEntityFactory::createReaderFromFile ( Type::XLSX );
		$oReader->open ( $_FILES ['file'] ['tmp_name'] );
		$pathinfo = pathinfo ( $_FILES ["file"] ["name"] );
		$oCooperationProductDAO = new CooperationProductDAO ();
		if (($pathinfo ['extension'] == 'xlsx' || $pathinfo ['extension'] == 'xls') && $_FILES ['file'] ['size'] > 0) {
		} else {
			echo "PLEASE SELECT A VALID EXCEL FILE";
		}
		foreach ( $oReader->getSheetIterator () as $oSheet ) {
			$count = 1;
			foreach ( $oSheet->getRowIterator () as $oRow ) {
				$aValue = $oRow->toArray ();
				if ($count == 1) {
					if ($aValue [0] != "협력사 명") {
						break;
					}
				} else {
					try {
						$sCooperationProductSeq = $aValue [2];
						$sCooperationCompanySeq = $aValue [1];
						$nCategorySeq = $aValue [3];
						$sName = $aValue [4];
						$sURL = $aValue [5];
						$nPrice = $aValue [6];
						$nMobilePrice = $aValue [7];
						$oCooperationProduct = new CooperationProduct ( $aValue [2], $aValue [1], $aValue [3], $aValue [4], $aValue [5], $aValue [6], $aValue [7] );
						$oTempCooperationProduct = $oCooperationProductDAO->findByCooperationProductSeq ( $sCooperationProductSeq );
						if ($oTempCooperationProduct == null) {
							$oCooperationProductDAO->save ( $oCooperationProduct );
						} else {
							$oCooperationProductDAO->update ( $oCooperationProduct );
						}
					} catch ( Exception $e ) {
						array_push ( $aError, $sCooperationProductSeq );
						echo 'Message: ' . $aError;
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
	} else {
		echo "UPLOAD AN EXCEL FILE";
	}
}
function insertStandardProductList() {
	if (! empty ( $_FILES ['file'] ['name'] )) {
		$oReader = ReaderEntityFactory::createReaderFromFile ( Type::XLSX );
		$oReader->open ( $_FILES ['file'] ['tmp_name'] );
		$pathinfo = pathinfo ( $_FILES ["file"] ["name"] );
		$oStandardProductDAO = new StandardProductDAO ();
		$oCategory = new CategoryDAO ();
		if (($pathinfo ['extension'] == 'xlsx' || $pathinfo ['extension'] == 'xls') && $_FILES ['file'] ['size'] > 0) {
		} else {
			echo "PLEASE SELECT A VALID EXCEL FILE";
		}
		foreach ( $oReader->getSheetIterator () as $oSheet ) {
			$count = 1;
			foreach ( $oSheet->getRowIterator () as $oRow ) {
				if ($count > 1) {
					try {

						$aValue = $oRow->toArray ();
						$nStandardProductSeq = test_input ( $aValue [0] );
						$nCategorySeq = test_input ( $aValue [1] );
						$sName = test_input ( $aValue [2] );
						$nLowestPrice = test_input ( $aValue [3] );
						$nMobileLowestPrice = test_input ( $aValue [4] );
						$nAveragePrice = test_input ( $aValue [5] );
						$nCooperationCompayCount = test_input ( $aValue [6] );
						$oStandardProductDAO->save ( $nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount );
					} catch ( Exception $e ) {
						echo 'Message: ' . $e->getMessage ();
					}
				}
				$count ++;
			}
			echo "successfully uploaded!";
		}

		$oReader->close ();
	} else {
		echo "UPLOAD AN EXCEL FILE";
	}
}
function testInput($data) {
	$data = trim ( $data );
	$data = stripslashes ( $data );
	$data = htmlspecialchars($data);
    return $data;
}

?>