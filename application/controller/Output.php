<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/service/CooperationProduct.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/service/StandardProduct.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/service/Category.php';
class OutputController
{

    private $oStandardProductService;

    private $oCooperationProductService;

    private $oCategoryService;
    
    function __construct() {
        $this->oStandardProductService = new StandardProductService();
        $this->oCooperationProductService = new CooperationProductService();
        $this->oCategoryService = new CategoryService();
    }
    
    public function findCategoryList(){
        $aResponse = $this->oCategoryService->findAll();
        return json_encode($aResponse, JSON_PRETTY_PRINT);
    }
    
    public function findListByCategorySeq(){
        $nCategorySeq = trim($_POST['categorySeq']);
        $aResponse = $this->oStandardProductService->findListByCategorySeq($nCategorySeq);
        return json_encode($aResponse, JSON_PRETTY_PRINT);
    }
}