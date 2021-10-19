<?php declare(strict_types=1);
require_once '/media/sf_ExcelFileHandlingProject/application/dao/StandardProduct.php';
require_once '/media/sf_ExcelFileHandlingProject/application/service/StandardProduct.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/media/sf_ExcelFileHandlingProject/include/pdoConnect.php';
use PHPUnit\Framework\TestCase;

class StandardProductTest extends TestCase {
	
	/** @test */
	public function checkStandardProductList(): void {
		//given
		$oStandardProductService = new StandardProductService();
		
		//when
		$aStandardProductList = $oStandardProductService->findListByCategorySeq(1, 1, 1, 57906);
		
		//then
		$this->assertArrayHasKey('aPageData', $aStandardProductList);
		$this->assertArrayHasKey('nCurrentCount', $aStandardProductList);
		$this->assertGreaterThan(2, count($aStandardProductList));
	}
	
	/** @test */
	public function checkUpload(): void {
		//given
		$oStandardProductDAO = new StandardProductDAO;
		$oPdo = new pdoConnect ();
		$pdo = $oPdo->connectPdo ();
		
		//when
		$pdo->beginTransaction ();
		$this->assertEquals(true, $oStandardProductDAO->save($pdo, '0', '57906', 'test', 0, 0, 0, 0));
		$pdo->rollBack ();
	}
	
	/** @test */
	public function makeSortPriorityList(): void {
		$oStandardProductService = new StandardProductService();
		$nOption = 1;
		$aSortPriority = $oStandardProductService->makeSortPriorityList($nOption);
		$this->assertEquals(5, count($aSortPriority));
	}
}
