<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/CooperationCompany.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/CooperationProduct.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dto/CooperationProduct.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/src/Spout/Autoloader/autoload.php';
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
@set_time_limit ( 0 );
class CooperationProductService {
	private $oCooperationProductDAO;
	private $oCooperationCompanyDAO;
	private $oReader;
	function __construct() {
		$this->oReader = ReaderEntityFactory::createReaderFromFile ( Type::XLSX );
		$this->oCooperationProductDAO = new CooperationProductDAO ();
		$this->oCooperationCompanyDAO = new CooperationCompanyDAO ();
		
		$oPdo = new pdoConnect ();
		$this->pdo = $oPdo->connectPdo ();
	}
	public function checkCooperationCompanyModification($sName, $sRecentName) {
		if ($sName != $sRecentName) {
			return true;
		}
		return false;
	}
	public function checkCooperationProductModification($oCooperationProduct, $oRecentCooperationProduct) {
		if ($oCooperationProduct ['$nCategorySeq'] != $oRecentCooperationProduct ['$nCategorySeq'] || $oCooperationProduct ['$nCategorySeq'] != $oRecentCooperationProduct ['$nCategorySeq'] || $oCooperationProduct ['$sName'] != $oRecentCooperationProduct ['$sName'] || $oCooperationProduct ['$sURL'] != $oRecentCooperationProduct ['$sURL'] || $oCooperationProduct ['$nPrice'] != $oRecentCooperationProduct ['$nPrice'] || $oCooperationProduct ['$nMobilePrice'] != $oRecentCooperationProduct ['$nMobilePrice'] || $oCooperationProduct ['$dtInputDate'] != $oRecentCooperationProduct ['$dtInputDate']) {
			return true;
		}

		return false;
	}
	public function upload($sFilePath) {
		$this->oReader->open ( $sFilePath );
		$aResponse = array ();
		$aErrorRow = array ();
		$successRowCount = 0;

		foreach ( $this->oReader->getSheetIterator () as $oSheet ) {
			$count = 1;
			try {
				$this->pdo->beginTransaction ();
				foreach ( $oSheet->getRowIterator () as $oRow ) {
					$aValue = $oRow->toArray ();
					if ($count == 1) {
						if (count ( $aValue ) != 9 || '협력사 명' != $aValue [0] || '협력사 코드' != $aValue [1] || '협력사 상품코드 ' != $aValue [2] || '대분류' != $aValue [3] || '협력사 상품명 ' != $aValue [4] || '협력사 url' != $aValue [5] || '가격' != $aValue [6] || '모바일가격' != $aValue [7] || '입력일' != $aValue [8]) {
							break;
						}
					} else {
						try {
							$sCooperationProductSeq = testInput ( $aValue [2] );
							$sCooperationCompanySeq = testInput ( $aValue [1] );
							$sCooperationCompanyName = testInput ( $aValue [0] );
							$nCategorySeq = testInput ( $aValue [3] );
							$sName = testInput ( $aValue [4] );
							$sURL = testInput ( $aValue [5] );
							$nPrice = testInput ( $aValue [6] );
							$nMobilePrice = testInput ( $aValue [7] );
							$dtInputDate = testInput ( date_format ( $aValue [8], "Y-m-d" ) );

							$oCooperationProduct = new CooperationProduct ( $sCooperationProductSeq, $sCooperationCompanySeq, $nCategorySeq, $sName, $sURL, $nPrice, $nMobilePrice, $dtInputDate );
							if ($this->oCooperationCompanyDAO->findByCooperationCompanySeq ( $sCooperationCompanySeq ) == null) {
								$this->oCooperationCompanyDAO->save ( $sCooperationCompanySeq, $sCooperationCompanyName );
							} else if (checkCooperationCompanyModification ( $oTempCooperationCompany ['sName'], $sCooperationCompanyName )) {
								$this->oCooperationCompanyDAO->update ( $sCooperationCompanySeq, $sCooperationCompanyName );
							}

							if ($this->oCooperationProductDAO->findByCooperationProductSeq ( $sCooperationProductSeq ) == null) {
								$this->oCooperationProductDAO->save ( $oCooperationProduct );
							} else if (checkCooperationProductModification ( $oTempCooperationProduct, $oCooperationProduct )) {
								$this->oCooperationProductDAO->update ( $oCooperationProduct );
							}
							$successRowCount ++;
						} catch ( Exception $e ) {
							$aErrorRow [] = $sCooperationProductSeq;
						}
					}
					if(count % 100 == 0) {
						$this->pdo->commit ();
					}
					$count ++;
				}
			} catch ( PDOException $e ) {
				$this->pdo->rollBack ();
			}
		}
		if ($successRowCount == 0) {
			$aResponse ['code'] = 402;
		} else {
			$aResponse ['errorRow'] = $aErrorRow;
			$aResponse ['errorRowCount'] = count ( $aErrorRow );
			$aResponse ['code'] = 200;
			$aResponse ['successRowCount'] = $successRowCount - 1;
		}
		$this->oReader->close ();
		return $aResponse;
	}
	public function download($aCooperationProduct) {
		$dtCreateDate = date ( "Y-m-d H:i:s", time () );
		try {
			$sFilePath = getcwd () . '/' . $dtCreateDate . 'cooperationProduct.xlsx';
			$oWriter = WriterEntityFactory::createXLSXWriter ();
			$oWriter->openToFile ( $sFilePath );
			$aDataList = [ 
					[ 
							'협력사 명',
							'협력사 코드',
							'협력사 상품명',
							'협력사 URL',
							'가격',
							'모바일 가격',
							'입력일'
					]
			];
			foreach ( $aCooperationProduct as $oCooperationProduct ) {
				$aTempProduct = [ ];
				array_push ( $aTempProduct, $oCooperationProduct->sCooperationCompanyName );
				array_push ( $aTempProduct, $oCooperationProduct->sCooperationCompanySeq );
				array_push ( $aTempProduct, $oCooperationProduct->sName );
				array_push ( $aTempProduct, $oCooperationProduct->sURL );
				array_push ( $aTempProduct, $oCooperationProduct->nPrice );
				array_push ( $aTempProduct, $oCooperationProduct->nMobilePrice );
				array_push ( $aTempProduct, $oCooperationProduct->dtInputDate );
				array_push ( $aDataList, $aTempProduct );
			}
			foreach ( $aDataList as $aData ) {
				$aCell = [ 
						WriterEntityFactory::createCell ( $aData [0] ),
						WriterEntityFactory::createCell ( $aData [1] ),
						WriterEntityFactory::createCell ( $aData [2] ),
						WriterEntityFactory::createCell ( $aData [3] ),
						WriterEntityFactory::createCell ( $aData [4] ),
						WriterEntityFactory::createCell ( $aData [5] ),
						WriterEntityFactory::createCell ( $aData [6] )
				];

				$aSingleRow = WriterEntityFactory::createRow ( $aCell );
				$oWriter->addRow ( $aSingleRow );
			}
			$oWriter->close ();
			return 200;
		} catch ( Exception $e ) {
			return 400;
		}
	}
	function findListByCategorySeq($sOption, $nCurrentPage, $nOrder, $nCategorySeq) {
		$nCooperationProductListLength = $this->oCooperationProductDAO->countByCategorySeq ( $nCategorySeq );
		$aPageData = paging ( $nCooperationProductListLength, $nCurrentPage );
		$aCooperationProduct = $this->oCooperationProductDAO->findByCategorySeqOrderByNameASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
		if ($sOption == 1) { // 상품 코드
			if ($nOrder == 1) {
				$aCooperationProduct = $this->oCooperationProductDAO->findByCategorySeqOrderByNameASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			} else {
				$aCooperationProduct = $this->oCooperationProductDAO->findByCategorySeqOrderByNameDESC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			}
		} else if ($sOption == 2) { // 상품 명
			if ($nOrder == 1) {
				$aCooperationProduct = $this->oCooperationProductDAO->findByCategorySeqOrderByInputDateASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			} else {
				$aCooperationProduct = $this->oCooperationProductDAO->findByCategorySeqOrderByInputDateDESC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			}
		}
		$aCooperationProduct ['nCurrentCount'] = count ( $aCooperationProduct );
		$aCooperationProduct ['aPageData'] = $aPageData;
		return $aCooperationProduct;
	}
	function testInput($data) {
		$data = trim ( $data );
		$data = stripslashes ( $data );
		$data = htmlspecialchars ( $data );
		return $data;
	}
}