<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/paging.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/StandardProduct.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/src/Spout/Autoloader/autoload.php';
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
		$aError [] = array ();
		foreach ( $this->oReader->getSheetIterator () as $oSheet ) {
			$count = 1;
			foreach ( $oSheet->getRowIterator () as $oRow ) {
				try {
					if ($count > 1) {
						$aValue = $oRow->toArray ();
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
					array_push ( $aError, $nStandardProductSeq );
					// return $e->getMessage();
				}
			}
		}
		$this->oReader->close ();
		return $aError;
	}
	function findListByCategorySeq($sOption, $nCurrentPage, $nOrder, $nCategorySeq) {
		$nStandardProductListLength = $this->oStandardProductDAO->countAll ();
		$aPageData = paging ( $nStandardProductListLength, $nCurrentPage );
		if ($sOption == 1) { // 상품 코드
			if ($nOrder == 1) {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderBySeqASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			} else {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderBySeqDESC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			}
		} else if ($sOption == 3) { // 상품 명
			if ($nOrder == 1) {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderBySeqASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			} else {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderBySeqASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			}
		} else if ($sOption == 4) { // 최저가
			if ($nOrder == 1) {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderBySeqASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			} else {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderBySeqASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			}
		} else if ($sOption == 5) { // 모바일 최저가
			if ($nOrder == 1) {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderBySeqASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			} else {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderBySeqASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			}
		} else if ($sOption == 7) { // 업체 수
			if ($nOrder == 1) {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderBySeqASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			} else {
				$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderBySeqASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq );
			}
		}
		$aStandardProduct ['nCurrentCount'] = count ( $aStandardProduct );
		$aStandardProduct ['aPageData'] = $aPageData;
		return $aStandardProduct;
	}
	function findAll($nCurrentPage) {
		$nStandardProductListLength = $this->oStandardProductDAO->countAll ();
		$aPageData = paging ( $nStandardProductListLength, $nCurrentPage );
		$aStandardProduct = $this->oStandardProductDAO->findOrderBySeqASC ( $aPageData ['nStartCount'], $aPageData ['nBlockCount'] );
		$aStandardProduct ['aPageData'] = $aPageData;
		$aStandardProduct ['nCurrentCount'] = count ( $aStandardProduct );
		return $aStandardProduct;
	}
	function testInput($data) {
		$data = trim ( $data );
		$data = stripslashes ( $data );
		$data = htmlspecialchars ( $data );
		return $data;
	}
}