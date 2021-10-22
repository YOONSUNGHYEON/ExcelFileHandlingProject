<?php
class StandardProduct {
	private $nStandardProductSeq;
	private $nCategorySeq;
	private $sName;
	private $nLowestPrice;
	private $nMobileLowestPrice;
	private $nAveragePrice;
	private $nCooperationCompayCount;

	function __construct($nStandardProductSeq, $nCategorySeq, $sName, $nLowestPrice, $nMobileLowestPrice, $nAveragePrice, $nCooperationCompayCount) {
		$this->nStandardProductSeq = $nStandardProductSeq;
		$this->nCategorySeq = $nCategorySeq;
		$this->sName = $sName;
		$this->nLowestPrice = $nLowestPrice;
		$this->nMobileLowestPrice = $nMobileLowestPrice;
		$this->nAveragePrice = $nAveragePrice;
		$this->nCooperationCompayCount = $nCooperationCompayCount;
	}
	public function getStandardProductSeq() {
		return $this->nStandardProductSeq;
	}
	public function getCategorySeq() {
		return $this->nCategorySeq;
	}
	public function getName() {
		return $this->sName;
	}
	public function getLowestPrice() {
		return $this->nLowestPrice;
	}
	public function getMobileLowestPrice() {
		return $this->nMobileLowestPrice;
	}
	public function getAveragePrice() {
		return $this->nAveragePrice;
	}
	public function getCooperationCompayCount() {
		return $this->nCooperationCompayCount;
	}
}