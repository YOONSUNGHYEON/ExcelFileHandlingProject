<?php
require_once '/media/sf_ExcelFileHandlingProject/include/paging.php';
require_once '/media/sf_ExcelFileHandlingProject/application/dao/StandardProduct.php';
require_once '/media/sf_ExcelFileHandlingProject/application/dto/StandardProduct.php';
require_once '/media/sf_ExcelFileHandlingProject/src/Spout/Autoloader/autoload.php';
require_once '/media/sf_ExcelFileHandlingProject/include/pdoConnect.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
class StandardProductService {
	private $oStandardProductDAO;
	private $oReader;
	private $pdo;
	function __construct() {
		$this->oReader = ReaderEntityFactory::createReaderFromFile ( Type::XLSX );
		$this->oStandardProductDAO = new StandardProductDAO ();
		$oPdo = new pdoConnect ();
		$this->pdo = $oPdo->connectPdo ();
	}
	/* 
	 * 업로드된 엑셀 파일 칼럼 유효성 확인하기
	 */
	function checkColumn($aColumn) {
		if(count ( $aColumn ) != 7){
			return false;
		} else if('상품코드' != $aValue [0] || '카테고리' != $aValue [1] || '상품명' != $aValue [2] || '최저가' != $aValue [3] || '모바일 최저가' != $aValue [4] || '평균가' != $aValue [5] || '업체수' != $aValue [6]) {
			return false;
		}
		return true;
	}
	
	function upload($sFilePath) {
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
						$this->pdo->beginTransaction ();
						if (!checkColumn($aValue)) {
							break;
						}
					} else {
						try {
							if ($count > 1) {
								$nStandardProductSeq = $this->testInput ( $aValue [0] );
								$nCategorySeq = $this->testInput ( $aValue [1] );
								$sName = $this->testInput ( $aValue [2] );
								$nLowestPrice = $this->testInput ( $aValue [3] );
								$nMobileLowestPrice = $this->testInput ( $aValue [4] );
								$nAveragePrice = $this->testInput ( $aValue [5] );
								$nCooperationCompayCount = $this->testInput ( $aValue [6] );
								if ($this->oStandardProductDAO->findByStandardProductSeq ( $this->pdo, $nStandardProductSeq ) == null) {
									$this->oStandardProductDAO->save ( $this->pdo, $nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount );
								} else {
									$this->oStandardProductDAO->update ( $this->pdo, $nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount );
								}
								
							}
						} catch ( Exception $e ) {
							$aErrorRow [] = [ 
									seq => $nStandardProductSeq,
									message => $e->getMessage ()
							];
						}
					}
					if (1000 == $count) {
						$this->pdo->commit ();
						$this->pdo->beginTransaction ();
					}
					$successRowCount ++;
					$count++;
				}
				
			} catch ( PDOException $e ) {
				$this->pdo->rollBack ();
				$this->pdo->beginTransaction ();
			}
			finally {
				$this->pdo->commit ();
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
		return $aResponse;
	}

	/* 정렬 우선 순위 리스트 만들기 */
	function makeSortPriorityList($nOption) {
		$aSortPriority = array (
				[ 
						"optionSeq" => 1,
						"optionName" => "nStandardProductSeq"
				],
				[ 
						"optionSeq" => 3,
						"optionName" => "sName"
				],
				[ 
						"optionSeq" => 4,
						"optionName" => "nLowestPrice"
				],
				[ 
						"optionSeq" => 5,
						"optionName" => "nMobileLowestPrice"
				],
				[ 
						"optionSeq" => 7,
						"optionName" => "nCooperationCompayCount"
				]
		);

		$aNewSortPriority = array ();
		if ($nOption == 1) {
			array_push ( $aNewSortPriority, 'nStandardProductSeq' );
		} else if ($nOption == 3) {
			array_push ( $aNewSortPriority, 'sName' );
		} else if ($nOption == 4) {
			array_push ( $aNewSortPriority, 'nLowestPrice' );
		} else if ($nOption == 5) {
			array_push ( $aNewSortPriority, 'nMobileLowestPrice' );
		} else if ($nOption == 7) {
			array_push ( $aNewSortPriority, 'nCooperationCompayCount' );
		}

		foreach ( $aSortPriority as $oSortPriority ) {
			if ($oSortPriority ['optionSeq'] != $nOption) {
				array_push ( $aNewSortPriority, $oSortPriority ['optionName'] );
			}
		}
		return $aNewSortPriority;
	}

	/* 정렬되어진 기준 상품 20개 목록 가져오기 */
	function findListByCategorySeq($nOption, $nCurrentPage, $nOrder, $nCategorySeq) {
		$nStandardProductListLength = $this->oStandardProductDAO->countByCategorySeq ( $this->pdo, $nCategorySeq );
		$aPageData = paging ( $nStandardProductListLength, $nCurrentPage );
		$aSortPriority = $this->makeSortPriorityList ( $nOption );
		if ($nOrder == 1) {
			$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByASC ( $this->pdo, $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq, $aSortPriority );
		} else {
			$aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByDESC ( $this->pdo, $aPageData ['nStartCount'], $aPageData ['nBlockCount'], $nCategorySeq, $aSortPriority );
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
		$dtCreateDate = date ( "Ymd", time () );
		$dtCreateTime = date ( "His", time () );
		$aResponse = array ();
		try {
			$sFolderName = getcwd () . '/';
			$sFileName = $dtCreateDate . "_" . $dtCreateTime . '_기준상품.xlsx';
			$sFilePath = $sFolderName . $sFileName;
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
			$aResponse ['code'] = 200;
			$aResponse ['fileName'] = $sFileName;
			$aResponse ['filePath'] = $sFilePath;
			$oWriter->close ();
			//moveFile ( '/media/sf_ExcelFileHandlingProject/', '20211020_135438_기준상품.xlsx', '/media/sf_ExcelFileHandlingProject/20211020_135438_기준상품.xlsx');
		} catch ( Exception $e ) {
			$aResponse ['code'] = 400;
			$aResponse ['error'] = $e->getMessage ();
			$aResponse ['path'] = $sFilePath;
		} finally {
			return $aResponse;
		}
	}


}