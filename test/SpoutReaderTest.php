<?php
require_once '/media/sf_ExcelFileHandlingProject/src/Spout/Autoloader/autoload.php';

use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use PHPUnit\Framework\TestCase;
class SpoutReaderTest extends TestCase {

	/** @test */
	public function readStandardProductXLSX(): void {
		// given
		$oReader = ReaderEntityFactory::createReaderFromFile ( Type::XLSX );
		$oReader->open ( getcwd () . '/기준상품test.xlsx' );

		foreach ( $oReader->getSheetIterator () as $oSheet ) {
			$count = 1;
			foreach ( $oSheet->getRowIterator () as $oRow ) {
				$aValue = $oRow->toArray ();
				if ($count == 1) {
					$this->assertEquals ( 7, count ( $aValue ) );
					$this->assertEquals ( '카테고리', $aValue [1] );
					$this->assertEquals ( '상품명', $aValue [2] );
					$this->assertEquals ( '최저가', $aValue [3] );
					$this->assertEquals('모바일 최저가', $aValue [4] );
					$this->assertEquals ( '평균가', $aValue [5] );
					$this->assertEquals ( '업체수', $aValue [6] );
					$this->assertEquals ( '상품코드', $aValue [0] );
				}
				$count ++;
			}
		}
	}
}