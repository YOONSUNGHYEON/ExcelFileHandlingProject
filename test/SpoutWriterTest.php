<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . '/ExcelFileHandlingProject/src/Spout/Autoloader/autoload.php';
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

try {
    $filePath = getcwd().'/new-users.xlsx';
    
    $writer = WriterEntityFactory::createXLSXWriter();
    
    $writer->openToFile($filePath);
    
    // Here is data for XLSX file
    $data = [
        ['Name', 'Email'],
        ['Steve', 'steve@test.com'],
        ['David', 'david@test.com'],
    ];
    
    foreach ($data as $d) {
        $cells = [
            WriterEntityFactory::createCell($d[0]),
            WriterEntityFactory::createCell($d[1]),
        ];
        
        $singleRow = WriterEntityFactory::createRow($cells);
        $writer->addRow($singleRow);
    }
    
    $writer->close();
} catch(Exception $e) {
    echo $e->getMessage();
}