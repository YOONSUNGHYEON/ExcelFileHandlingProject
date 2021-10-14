<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dao/CooperationProduct.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/dto/CooperationProduct.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/src/Spout/Autoloader/autoload.php';
use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
@set_time_limit(0);    
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
        				$nCategorySeq = $aValue[3];
        				$sName = $aValue[4];
        				$sURL = $aValue[5];
        				$nPrice = $aValue[6];
        				$nMobilePrice = $aValue[7];
        				$oCooperationProduct = new CooperationProduct($aValue[2], $aValue[1], $aValue[3], $aValue[4], $aValue[5], $aValue[6], $aValue[7]);
        				$oTempCooperationProduct = $this->oCooperationProductDAO->findByCooperationProductSeq($sCooperationProductSeq);
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
    
    function testInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}