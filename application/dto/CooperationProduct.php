<?php
class CooperationProduct {
	private $sCooperationProductSeq;
	private $sCooperationCompanySeq;
	private $nCategorySeq;
	private $sName;
	private $sURL;
	private $nPrice;
	private $nMobilePrice;
	private $dtInputDate;
	function __construct($sCooperationProductSeq, $sCooperationCompanySeq, $nCategorySeq, $sName, $sURL, $nPrice, $nMobilePrice) {
		$this->sCooperationProductSeq = $sCooperationProductSeq;
		$this->sCooperationCompanySeq = $sCooperationCompanySeq;
		$this->nCategorySeq = $nCategorySeq;
		$this->sName = $sName;
		$this->sURL = $sURL;
		$this->nPrice = $nPrice;
		$this->nMobilePrice = $nMobilePrice;
	}
	public function getCooperationProductSeq() {
		return $this->sCooperationProductSeq;
	}
	public function getCooperationCompanySeq() {
		return $this->sCooperationCompanySeq;
	}
	public function getCategorySeq() {
		return $this->nCategorySeq;
	}
	public function getName() {
		return $this->sName;
	}
	public function getURL() {
		return $this->sURL;
	}
	public function getPrice() {
		return $this->nPrice;
	}
	public function getMobilePrice() {
		return $this->nMobilePrice;
	}
}