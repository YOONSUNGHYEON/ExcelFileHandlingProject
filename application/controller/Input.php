<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/service/CooperationProduct.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/service/StandardProduct.php';
class InputController {
	private $oStandardProductService;
	private $oCooperationProductService;
	function __construct() {
		$this->oStandardProductService = new StandardProductService ();
		$this->oCooperationProductService = new CooperationProductService ();
	}
	public function upload() {
		$nProductOption = trim ( $_POST ['productOption'] );
		if (! empty ( $nProductOption ) && ! empty ( $_FILES ['file'] ['name'] )) {
			$pathinfo = pathinfo ( $_FILES ["file"] ["name"] );
			if (($pathinfo ['extension'] == 'xlsx' || $pathinfo ['extension'] == 'xls') && $_FILES ['file'] ['size'] > 0) {
				if ($nProductOption == 2) {
					$aResponse = $this->oStandardProductService->upload ( $_FILES ['file'] ['tmp_name'] );
				} else if ($nProductOption == 3) {
					$aResponse = $this->oCooperationProductService->upload ( $_FILES ['file'] ['tmp_name'] );
				}
				
			} else {
				$aResponse['code'] = 401;
			}
		} else {
			$aResponse['code'] = 400;
		}
		return json_encode ($aResponse);
	}
}