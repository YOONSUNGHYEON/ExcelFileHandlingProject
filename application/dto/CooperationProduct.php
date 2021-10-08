<?php
class CooperationProduct {
	private $nCooperationProductSeq;
	private $nCooperationCompanySeq;
	private $nCategorySeq;
	private $sName;
	private $sURL;
	private $nPrice;
	private $nMobilePrice;
	private $dtInputDate;
	
	function __construct($nCooperationProductSeq, $nCooperationCompanySeq, $nCategorySeq, $sName, $sURL, $nPrice, $nMobilePrice) {
		$this->nCooperationProductSeq=$nCooperationProductSeq;
		$this->nCooperationCompanySeq=$nCooperationCompanySeq;
		$this->nCategorySeq=$nCategorySeq;
		$this->sName=$sName;
		$this->sURL=$sURL;
		$this->nPrice=$nPrice;
		$this->nMobilePrice=$nMobilePrice;
	}
	
	public function getCooperationProductSeq() {
		return $this->nCooperationProductSeq;
	}
	public function getCooperationCompanySeq() {
		return $this->nCooperationCompanySeq;
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