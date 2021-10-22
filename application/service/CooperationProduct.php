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
	private $oPDO;
	function __construct() {
		$this->oReader = ReaderEntityFactory::createReaderFromFile ( Type::XLSX );
		$this->oCooperationProductDAO = new CooperationProductDAO ();
		$this->oCooperationCompanyDAO = new CooperationCompanyDAO ();
		
		$oPDOConnect = new pdoConnect ();
		$this->oPDO = $oPDOConnect->connectPdo ();
	}

	public function upload($sFilePath) {
		$this->oReader->open ( $sFilePath );
		$aResponse = array ();
		$aErrorRow = array ();
		$successRowCount = 0;

		foreach ( $this->oReader->getSheetIterator () as $oSheet ) {
			$count = 1;
			try {
				
				foreach ( $oSheet->getRowIterator () as $oRow ) {
					$aValue = $oRow->toArray ();
					if ($count == 1) {
						$this->oPDO->beginTransaction ();
						if (count ( $aValue ) != 9 || '협력사 명' != $aValue [0] || '협력사 코드' != $aValue [1] || '협력사 상품코드 ' != $aValue [2] || '대분류' != $aValue [3] || '협력사 상품명 ' != $aValue [4] || '협력사 url' != $aValue [5] || '가격' != $aValue [6] || '모바일가격' != $aValue [7] || '입력일' != $aValue [8]) {
							break;
						}
					} else {
						try {
							$sCooperationProductSeq = $this->testInput ( $aValue [2] );
							$sCooperationCompanySeq = $this->testInput ( $aValue [1] );
							$sCooperationCompanyName = $this->testInput ( $aValue [0] );
							$nCategorySeq = $this->testInput ( $aValue [3] );
							$sName = $this->testInput ( $aValue [4] );
							$sURL = $this->testInput ( $aValue [5] );
							$nPrice = $this->testInput ( $aValue [6] );
							$nMobilePrice = $this->testInput ( $aValue [7] );
							$dtInputDate = $this->testInput ( date_format ( $aValue [8], "Y-m-d" ) );

							$oCooperationProduct = new CooperationProduct ( $sCooperationProductSeq, $sCooperationCompanySeq, $nCategorySeq, $sName, $sURL, $nPrice, $nMobilePrice, $dtInputDate );
							if ($this->oCooperationCompanyDAO->findByCooperationCompanySeq ($this->oPDO, $sCooperationCompanySeq ) == null) {
								$this->oCooperationCompanyDAO->save ($this->oPDO, $sCooperationCompanySeq, $sCooperationCompanyName );
							} else  {
								$this->oCooperationCompanyDAO->update ($this->oPDO, $sCooperationCompanySeq, $sCooperationCompanyName );
							}
							$oTempCooperationProduct = $this->oCooperationProductDAO->findByCooperationProductSeq ($this->oPDO, $sCooperationProductSeq );
							if ($oTempCooperationProduct == null) {
								$this->oCooperationProductDAO->save ($this->oPDO, $oCooperationProduct );
							} else  {
								$this->oCooperationProductDAO->update ($this->oPDO, $oCooperationProduct );
							}
							$successRowCount ++;
						} catch ( Exception $e ) {
							$aErrorRow [] = $sCooperationProductSeq;
						}
					}
					if(count % 1000 == 0) {
						$this->oPDO->commit ();
						$this->oPDO->beginTransaction ();
					}
					$count ++;
				}
				$this->oPDO->commit ();
			} catch ( PDOException $e ) {
				$this->oPDO->rollBack ();
				$this->oPDO->beginTransaction ();
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
		$dtCreateDate = date ( "Ymd", time () );
		$dtCreateTime = date ( "His", time () );
		$aResponse = array ();
		try {
			$sFilePath = getcwd () . '/' . $dtCreateDate . "_" . $dtCreateTime . '_협력사상품.xlsx';
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
			$aResponse ['code'] = 200;
			$aResponse ['path'] = $sFilePath;
			$oWriter->close ();
		} catch ( Exception $e ) {
			$aResponse ['code'] = 400;
			$aResponse ['error'] = $e->getMessage ();
			$aResponse ['path'] = $sFilePath;
		} finally {
			return $aResponse;
		}
	}
	function findListByCategorySeq($sOption, $nCurrentPage, $nOrder, $nCategorySeq) {
		$nCooperationProductListLength = $this->oCooperationProductDAO->countByCategorySeq ($this->oPDO,  $nCategorySeq );
		$aPageData = paging ( $nCooperationProductListLength, $nCurrentPage );
		$aCooperationProduct = $this->oCooperationProductDAO->findByCategorySeqOrderByNameASC ($this->oPDO, $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
		if ($sOption == 1) { // 상품 코드
			if ($nOrder == 1) {
				$aCooperationProduct = $this->oCooperationProductDAO->findByCategorySeqOrderByNameASC ( $this->oPDO, $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			} else {
				$aCooperationProduct = $this->oCooperationProductDAO->findByCategorySeqOrderByNameDESC ( $this->oPDO, $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			}
		} else if ($sOption == 2) { // 상품 명
			if ($nOrder == 1) {
				$aCooperationProduct = $this->oCooperationProductDAO->findByCategorySeqOrderByInputDateASC ( $this->oPDO, $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			} else {
				$aCooperationProduct = $this->oCooperationProductDAO->findByCategorySeqOrderByInputDateDESC ( $this->oPDO, $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
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