<?php
function paging($nRowCount, $nCurrentPage = null) {
	$nBlockCount = 20;
	$nBlockPage = 10;

	if ($nCurrentPage == null) {
		$nCurrentPage = 1;
	}

	$nTotalPage = floor ( ($nRowCount - 1) / $nBlockCount ) + 1;

	if ($nTotalPage < $nCurrentPage) {
		$nCurrentPage = $nTotalPage;
	}

	if ($nCurrentPage < 1) {
		$nCurrentPage = 1;
	}

	$data ['nStartCount'] = ($nCurrentPage - 1) * $nBlockCount;

	$data ['nStartPage'] = floor ( ($nCurrentPage - 1) / $nBlockPage ) * $nBlockPage + 1;
	$data ['nEndPage'] = $data ['nStartPage'] + $nBlockPage - 1;

	if ($data ['nEndPage'] > $nTotalPage) {
		$data ['nEndPage'] = $nTotalPage;
	}

	$data ['aLimit'] = Array (
			$data ['nStartCount'],
			$nBlockCount
	);

	$data ['nCurrentPage'] = $nCurrentPage;
	$data ['nBlockPage'] = $nBlockPage;
	$data ['nTotalPage'] = $nTotalPage;
	$data ['nBlockCount'] = $nBlockCount;
	return $data;
}
