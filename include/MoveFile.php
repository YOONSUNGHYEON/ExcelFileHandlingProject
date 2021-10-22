<?php
$sFileName = $_GET ['fileName'];
$sFilePath = $_GET ['filePath'];
header ( "Content-type: application/vnd.ms-excel" );
header ( "Content-type: application/vnd.ms-excel; charset=utf-8" );
header ( "Content-Description: PHP4 Generated Data" );
header ( 'Content-Disposition: attachment; filename="' . $sFileName . '"' );
header ( 'Content-length: ' . filesize ( $sFilePath ) );
/* 파일을 서버에서 로컬로 옮기기 */

$fp = fopen ( $sFilePath, 'rb' );
fpassthru ( $fp );
fclose ( $fp );	
