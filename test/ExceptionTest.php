<?php declare(strict_types=1);
require_once '/media/sf_ExcelFileHandlingProject/application/dao/CooperationProduct.php';
require_once '/media/sf_ExcelFileHandlingProject/application/dto/CooperationProduct.php';
require_once $_SERVER ["DOCUMENT_ROOT"] . '/media/sf_ExcelFileHandlingProject/include/pdoConnect.php';
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{	
	public function testException(): void
	{
		$oPDOConnect = new pdoConnect ();
		$oPDO = $oPDOConnect->connectPdo ();
		
		$oCooperationProductDAO = new CooperationProductDAO();
		$oCooperationProduct = new CooperationProduct('test', 'EE715',
										57906, 'test', 'test', '2012-9-9', 0, 0);
		$oPDO->beginTransaction ();
		$this->expectException(PDOException::class);
		$this -> expectExceptionMessage("Error!");
		$oCooperationProductDAO->save($oPDO, $oCooperationProduct);
		$oPDO->rollBack ();		
	}
}
