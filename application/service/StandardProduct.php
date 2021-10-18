<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/paging.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/StandardProduct.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/src/Spout/Autoloader/autoload.php';
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class StandardProductService
{

    private $oStandardProductDAO;

    private $oReader;

    function __construct()
    {
        $this->oReader = ReaderEntityFactory::createReaderFromFile(Type::XLSX);
        $this->oStandardProductDAO = new StandardProductDAO();
    }

    function upload($sFilePath)
    {
        $this->oReader->open($sFilePath);
        $aError[] = array();
        foreach ($this->oReader->getSheetIterator() as $oSheet) {
            $count = 1;
            foreach ($oSheet->getRowIterator() as $oRow) {
                try {
                    if ($count > 1) {
                        $aValue = $oRow->toArray();
                        $nStandardProductSeq = $this->testInput($aValue[0]);
                        $nCategorySeq = $this->testInput($aValue[1]);
                        $sName = $this->testInput($aValue[2]);
                        $nLowestPrice = $this->testInput($aValue[3]);
                        $nMobileLowestPrice = $this->testInput($aValue[4]);
                        $nAveragePrice = $this->testInput($aValue[5]);
                        $nCooperationCompayCount = $this->testInput($aValue[6]);
                        $oStandardProduct = $this->oStandardProductDAO->findByStandardProductSeq($nStandardProductSeq);
                        if ($oStandardProduct == null) {
                            $this->oStandardProductDAO->save($nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount);
                        } else {
                            $this->oStandardProductDAO->update($nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount);
                        }
                    }
                    $count ++;
                } catch (Exception $e) {
                    array_push($aError, $nStandardProductSeq);
                    // return $e->getMessage();
                }
            }
        }
        $this->oReader->close();
        return $aError;
    }

    function findListByCategorySeq($sOption, $nCurrentPage, $nOrder, $nCategorySeq)
    {
        $nStandardProductListLength = $this->oStandardProductDAO->countByCategorySeq($nCategorySeq);
        $aPageData = paging($nStandardProductListLength, $nCurrentPage);
        if ($sOption == 1) { // 상품 코드
            if ($nOrder == 1) {
                $aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderBySeqASC($aPageData['nStartCount'], $aPageData['nBlockCount'], $nCategorySeq);
            } else {
                $aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderBySeqDESC($aPageData['nStartCount'], $aPageData['nBlockCount'], $nCategorySeq);
            }
        } else if ($sOption == 3) { // 상품 명
            if ($nOrder == 1) {
                $aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByNameASC($aPageData['nStartCount'], $aPageData['nBlockCount'], $nCategorySeq);
            } else {
                $aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByNameDESC($aPageData['nStartCount'], $aPageData['nBlockCount'], $nCategorySeq);
            }
        } else if ($sOption == 4) { // 최저가
            if ($nOrder == 1) {
                $aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByLowestPriceASC($aPageData['nStartCount'], $aPageData['nBlockCount'], $nCategorySeq);
            } else {
                $aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByLowestPriceDESC($aPageData['nStartCount'], $aPageData['nBlockCount'], $nCategorySeq);
            }
        } else if ($sOption == 5) { // 모바일 최저가
            if ($nOrder == 1) {
                $aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByMobileLowestPriceASC($aPageData['nStartCount'], $aPageData['nBlockCount'], $nCategorySeq);
            } else {
                $aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByMobileLowestPriceDESC($aPageData['nStartCount'], $aPageData['nBlockCount'], $nCategorySeq);
            }
        } else if ($sOption == 7) { // 업체 수
            if ($nOrder == 1) {
                $aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByCooperationCompayCountASC($aPageData['nStartCount'], $aPageData['nBlockCount'], $nCategorySeq);
            } else {
                $aStandardProduct = $this->oStandardProductDAO->findByCategorySeqOrderByCooperationCompayCountDESC($aPageData['nStartCount'], $aPageData['nBlockCount'], $nCategorySeq);
            }
        }
        $aStandardProduct['nCurrentCount'] = count($aStandardProduct);
        $aStandardProduct['aPageData'] = $aPageData;
        return $aStandardProduct;
    }

    function findAll($nCurrentPage)
    {
        $nStandardProductListLength = $this->oStandardProductDAO->countAll();
        $aPageData = paging($nStandardProductListLength, $nCurrentPage);
        $aStandardProduct = $this->oStandardProductDAO->findOrderBySeqASC($aPageData['nStartCount'], $aPageData['nBlockCount']);
        $aStandardProduct['aPageData'] = $aPageData;
        $aStandardProduct['nCurrentCount'] = count($aStandardProduct);
        return $aStandardProduct;
    }

    function testInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function download($aStandardProduct)
    {
        try {
            $sFilePath = getcwd() . '/standardProduct.xlsx';
            $oWriter = WriterEntityFactory::createXLSXWriter();
            $oWriter->openToFile($sFilePath);
            $data = [
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
            foreach ($aStandardProduct as $oStandardProduct) {
                $aTempProduct = [];
                array_push($aTempProduct,  $oStandardProduct->nStandardProductSeq);
                array_push($aTempProduct,  $oStandardProduct->sCategoryName);
                array_push($aTempProduct,  $oStandardProduct->sName);
                array_push($aTempProduct,  $oStandardProduct->nLowestPrice);
                array_push($aTempProduct,  $oStandardProduct->nMobileLowestPrice);
                array_push($aTempProduct,  $oStandardProduct->nAveragePrice);
                array_push($aTempProduct,  $oStandardProduct->nCooperationCompayCount);
                array_push($data, $aTempProduct);
            }
            foreach ($data as $d) {
                $cells = [
                    WriterEntityFactory::createCell($d[0]),
                    WriterEntityFactory::createCell($d[1]),
                    WriterEntityFactory::createCell($d[2]),
                    WriterEntityFactory::createCell($d[3]),
                    WriterEntityFactory::createCell($d[4]),
                    WriterEntityFactory::createCell($d[5]),
                    WriterEntityFactory::createCell($d[6]),
                ];

                $singleRow = WriterEntityFactory::createRow($cells);
                $oWriter->addRow($singleRow);
            }

            return $oWriter->close();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}