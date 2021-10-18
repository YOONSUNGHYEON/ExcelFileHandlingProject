<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/paging.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/StandardProduct.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/src/Spout/Autoloader/autoload.php';
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
class StandardProductService {
	private $oStandardProductDAO;
	private $oReader;
	function __construct() {
		$this->oReader = ReaderEntityFactory::createReaderFromFile ( Type::XLSX );
		$this->oStandardProductDAO = new StandardProductDAO ();
	}
	function upload($sFilePath) {
		$this->oReader->open ( $sFilePath );
		$aResponse = array();
		$aErrorRow = array();
		foreach ( $this->oReader->getSheetIterator () as $oSheet ) {
			$count = 1;
			foreach ( $oSheet->getRowIterator () as $oRow ) {
				$aValue = $oRow->toArray ();
				if ($count == 1) {
					if (count($aValue)!=7 || ('상품코드' != $aValue [0] || '카테고리' != $aValue [1] || '상품명' != $aValue [2] 
							|| '최저가' != $aValue [3] || '모바일 최저가' != $aValue [4] || '평균가' != $aValue [5] || '업체수' != $aValue [6])){
						$aResponse['code'] = 402;
						return $aResponse; 
					}						
				}
				try {
					if ($count > 1) {
						$nStandardProductSeq = $this->testInput ( $aValue [0] );
						$nCategorySeq = $this->testInput ( $aValue [1] );
						$sName = $this->testInput ( $aValue [2] );
						$nLowestPrice = $this->testInput ( $aValue [3] );
						$nMobileLowestPrice = $this->testInput ( $aValue [4] );
						$nAveragePrice = $this->testInput ( $aValue [5] );
						$nCooperationCompayCount = $this->testInput ( $aValue [6] );
						$oStandardProduct = $this->oStandardProductDAO->findByStandardProductSeq ( $nStandardProductSeq );
						if ($oStandardProduct == null) {
							$this->oStandardProductDAO->save ( $nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount );
						} else {
							$this->oStandardProductDAO->update ( $nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount );
						}
					}
					$count ++;
				} catch ( Exception $e ) {
					$aErrorRow[] = $nStandardProductSeq;
				}
			}
		}
		
		$aResponse['errorRow'] =  $aErrorRow;
		$aResponse['errorRowCount'] =  count($aErrorRow);
		$aResponse['code'] = 200;
		$aResponse['successRowCount'] = $count-2;
		$this->oReader->close ();
		return $aResponse;
	}
	
	
	
	function findListByCategorySeq($sOption, $nCurrentPage, $nOrder, $nCategorySeq) {
		$nStandardProductListLength = $this->oStandardProductDAO->countByCategorySeq ( $nCategorySeq );
		$aPageData = paging ( $nStandardProductListLength, $nCurrentPage );
		if ($sOption == 1) { // 상품 코드
			if ($nOrder == 1) {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderBySeqASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			} else {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderBySeqDESC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			}
		} else if ($sOption == 3) { // 상품 명
			if ($nOrder == 1) {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByNameASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			} else {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByNameDESC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			}
		} else if ($sOption == 4) { // 최저가
			if ($nOrder == 1) {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByLowestPriceASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			} else {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByLowestPriceDESC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			}
		} else if ($sOption == 5) { // 모바일 최저가
			if ($nOrder == 1) {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByMobileLowestPriceASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			} else {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByMobileLowestPriceDESC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			}
		} else if ($sOption == 7) { // 업체 수
			if ($nOrder == 1) {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByCooperationCompayCountASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			} else {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByCooperationCompayCountDESC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			}
		}
		$aStandardProduct ['nCurrentCount'] = count ( $aStandardProduct );
		$aStandardProduct ['aPageData'] = $aPageData;
		return $aStandardProduct;
	}
	
	function testInput($data) {
		$data = trim ( $data );
		$data = stripslashes ( $data );
		$data = htmlspecialchars ( $data );
		return $data;
	}
	public function download($aStandardProduct) {
		$dtCreateDate = date ( "Y-m-d", time () );
		$dtCreateTime = date ( "H:i:s", time () );
		try {
			$sFilePath = getcwd () . '/' . $dtCreateDate . '_standardProduct.xlsx';
			$oWriter = WriterEntityFactory::createXLSXWriter ();
			$oWriter->openToFile ( $sFilePath );
			$aDataList = [ 
					[ 
							'상품코드',
							'카테고리',
							'상품명',
							'최저가',
							'모바일 최저가',
							'평균가',
							'업체수'
					]
			];
			foreach ( $aStandardProduct as $oStandardProduct ) {
				$aTempProduct = [ ];
				array_push ( $aTempProduct, $oStandardProduct->nStandardProductSeq );
				array_push ( $aTempProduct, $oStandardProduct->sCategoryName );
				array_push ( $aTempProduct, $oStandardProduct->sName );
				array_push ( $aTempProduct, $oStandardProduct->nLowestPrice );
				array_push ( $aTempProduct, $oStandardProduct->nMobileLowestPrice );
				array_push ( $aTempProduct, $oStandardProduct->nAveragePrice );
				array_push ( $aTempProduct, $oStandardProduct->nCooperationCompayCount );
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
			return $sFilePath;
		} catch ( Exception $e ) {
			return 400;
		}
	}
}