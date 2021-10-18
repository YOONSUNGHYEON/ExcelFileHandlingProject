<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/CooperationCompany.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/CooperationProduct.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dto/CooperationProduct.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/src/Spout/Autoloader/autoload.php';
use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
@set_time_limit(0);

class CooperationProductService
{

    private $oCooperationProductDAO;

    private $oCooperationCompanyDAO;

    private $oReader;

    function __construct()
    {
        $this->oReader = ReaderEntityFactory::createReaderFromFile(Type::XLSX);
        $this->oCooperationProductDAO = new CooperationProductDAO();
        $this->oCooperationCompanyDAO = new CooperationCompanyDAO();
    }

    public function upload($sFilePath)
    {
        $this->oReader->open($sFilePath);
        $aError[] = array();
        foreach ($this->oReader->getSheetIterator() as $oSheet) {
            $count = 1;
            foreach ($oSheet->getRowIterator() as $oRow) {
                $aValue = $oRow->toArray();
                if ($count == 1) {
                    if ($aValue[0] != "협력사 명") {
                        break;
                    }
                } else {
                    try {
                        $sCooperationProductSeq = $aValue[2];
                        $sCooperationCompanySeq = $aValue[1];
                        $sCooperationCompanyName = $aValue[0];
                        $nCategorySeq = $aValue[3];
                        $sName = $aValue[4];
                        $sURL = $aValue[5];
                        $nPrice = $aValue[6];
                        $nMobilePrice = $aValue[7];
                        $oCooperationProduct = new CooperationProduct($aValue[2], $aValue[1], $aValue[3], $aValue[4], $aValue[5], $aValue[6], $aValue[7]);
                        $oTempCooperationProduct = $this->oCooperationProductDAO->findByCooperationProductSeq($sCooperationProductSeq);
                        $oTempCooperationCompany = $this->oCooperationCompanyDAO->findByCooperationCompanySeq($sCooperationCompanySeq);

                        if ($oTempCooperationCompany == null) {
                            $this->oCooperationCompanyDAO->save($sCooperationCompanySeq, $sCooperationCompanyName);
                        } else {
                            $this->oCooperationCompanyDAO->update($sCooperationCompanySeq, $sCooperationCompanyName);
                        }

                        if ($oTempCooperationProduct == null) {
                            $this->oCooperationProductDAO->save($oCooperationProduct);
                        } else {
                            $this->oCooperationProductDAO->update($oCooperationProduct);
                        }
                    } catch (Exception $e) {
                        array_push($aError, $sCooperationProductSeq);
                    }
                }
                $count ++;
            }
        }

        $this->oReader->close();
        return $aError;
    }

    function findListByCategorySeq($sOption, $nCurrentPage, $nOrder, $nCategorySeq)
    {
        $nCooperationProductListLength = $this->oCooperationProductDAO->countByCategorySeq($nCategorySeq);
        $aPageData = paging($nCooperationProductListLength, $nCurrentPage);
        $aStandardProduct = $this->oCooperationProductDAO->findByCategorySeqOrderBySeqASC($aPageData['nStartCount'], $aPageData['nBlockCount'], $nCategorySeq);
        /*if ($sOption == 1) { // 상품 코드
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
        }*/
        $aStandardProduct['nCurrentCount'] = count($aStandardProduct);
        $aStandardProduct['aPageData'] = $aPageData;
        return $aStandardProduct;
    }

    function testInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}