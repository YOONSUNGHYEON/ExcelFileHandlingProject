<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/service/CooperationProduct.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/service/StandardProduct.php';

class InputController
{

    private $oStandardProductService;

    private $oCooperationProductService;

    function __construct()
    {
        $this->oStandardProductService = new StandardProductService();
        $this->oCooperationProductService = new CooperationProductService();
    }

    public function upload()
    {
        $nProductOption = trim($_POST['productOption']);

        if (! empty($nProductOption) && ! empty($_FILES['file']['name'])) {
            $pathinfo = pathinfo($_FILES["file"]["name"]);
            if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['file']['size'] > 0) {
                if ($nProductOption == 2) {
                    $nResponse = $this->oStandardProductService->upload($_FILES['file']['tmp_name']);
                } else if ($nProductOption == 3) {
                    $nResponse = $this->oCooperationProductService->upload($_FILES['file']['tmp_name']);
                }
                return json_encode($nResponse, JSON_PRETTY_PRINT);
            } else {
                return 401;
            }
        } else {
            return 400;
        }
    }
}