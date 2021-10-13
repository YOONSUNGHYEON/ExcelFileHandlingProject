<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/CooperationProduct.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dto/CooperationProduct.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/src/Spout/Autoloader/autoload.php';
use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class CooperationProductService
{

    private $oCooperationProductDAO;

    private $oReader;

    function __construct()
    {
        $this->oReader = ReaderEntityFactory::createReaderFromFile(Type::XLSX);
        $this->oCooperationProductDAO = new CooperationProductDAO();
    }

    public function upload($sFilePath)
    {
        $this->oReader->open($sFilePath);
        $aError[] = array();
        foreach ( $this->oReader->getSheetIterator() as $oSheet) {
            $count = 1;
            foreach ($oSheet->getRowIterator() as $oRow) {
                try {
                    $aValue = $oRow->toArray();
                    if ($count == 1) {
                        if ($aValue[0] != "협력사 명") {
                            break;
                        }
                    } else {
                        $sCooperationProductSeq = $this->testInput($aValue[2]);
                        $sCooperationCompanySeq = $this->testInput($aValue[1]);
                        $nCategorySeq = $this->testInput($aValue[3]);
                        $sName = $this->testInput($aValue[4]);
                        $sURL = $this->testInput($aValue[5]);
                        $nPrice = $this->testInput($aValue[6]);
                        $nMobilePrice = $this->testInput($aValue[7]);
                        $oCooperationProduct = $this->oCooperationProductDAO->findByCooperationProductSeq($sCooperationProductSeq);
                        $oCooperationProduct = new CooperationProduct($sCooperationProductSeq, $sCooperationCompanySeq, $nCategorySeq, 
                                                                        $sName, $sURL, $nPrice, $nMobilePrice);
                        if ($oCooperationProduct == null) {
                            $this->oCooperationProductDAO->save($oCooperationProduct);
                        } else {
                            $this->oCooperationProductDAO->update($oCooperationProduct);
                        }
                    }
                    $count ++;
                } catch (Exception $e) {
                    //array_push($aError, $sCooperationProductSeq);
                   return 'Message: ' . $e->getMessage();
                }
            }
        }
        $this->oReader->close();
        return $aError;
    }
    
    function testInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}