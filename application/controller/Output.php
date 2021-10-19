<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/service/CooperationProduct.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/service/StandardProduct.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/application/service/Category.php';
class OutputController {
	private $oStandardProductService;
	private $oCooperationProductService;
	private $oCategoryService;
	function __construct() {
		$this->oStandardProductService = new StandardProductService ();
		$this->oCooperationProductService = new CooperationProductService ();
		$this->oCategoryService = new CategoryService ();
	}
	public function findCategoryList() {
		$aCategory = $this->oCategoryService->findAll ();
		if ($aCategory == false) {
			$aResponse ['code'] = 500;
		} else if ($aCategory == null) {
			$aResponse ['code'] = 400;
		} else {
			$aResponse ['code'] = 200;
			$aResponse ['data'] = $aCategory;
		}
        return json_encode ( $aResponse, JSON_PRETTY_PRINT );
	}
	public function findStandardProductListByCategorySeq() {
		if (isset ( $_POST ['option'] ) && isset ( $_POST ['page'] ) && isset ( $_POST ['order'] ) && isset ( $_POST ['categorySeq'] )) {
			$sOption = trim ( $_POST ['option'] );
			$nCurrentPage = trim ( $_POST ['page'] );
			$nOrder = trim ( $_POST ['order'] );
			$nCategorySeq = trim ( $_POST ['categorySeq'] );
			if (! empty ( $sOption ) && ! empty ( $nCurrentPage ) && ! empty ( $nOrder ) && ! empty ( $nCategorySeq )) {
				$aResponse = $this->oStandardProductService->findListByCategorySeq ( $sOption, $nCurrentPage, $nOrder, $nCategorySeq );
			}
		}

		return json_encode ( $aResponse, JSON_PRETTY_PRINT );
	}
	public function findCooperationProductListByCategorySeq() {
		if (isset ( $_POST ['option'] ) && isset ( $_POST ['page'] ) && isset ( $_POST ['order'] ) && isset ( $_POST ['categorySeq'] )) {
			$sOption = trim ( $_POST ['option'] );
			$nCurrentPage = trim ( $_POST ['page'] );
			$nOrder = trim ( $_POST ['order'] );
			$nCategorySeq = trim ( $_POST ['categorySeq'] );
			if (! empty ( $sOption ) && ! empty ( $nCurrentPage ) && ! empty ( $nOrder ) && ! empty ( $nCategorySeq )) {
				$aResponse = $this->oCooperationProductService->findListByCategorySeq ( $sOption, $nCurrentPage, $nOrder, $nCategorySeq );
			}
		}

		return json_encode ( $aResponse, JSON_PRETTY_PRINT );
	}
	public function download() {
		$aProduct = json_decode ( $_POST ['productArrObj'] );
		$aResponse = array ();
		$nProductType = $_POST ['productType'];
		if ($nProductType == 1) {
			$aResponse = $this->oStandardProductService->download ( $aProduct );

		} else {
			$aResponse ['code'] = $this->oCooperationProductService->download ( $aProduct );
		}
		return json_encode ( $aResponse );
	}
}