<?php
function avg()
{
	$args = func_get_args();
	$sum = 0;
	foreach ($args as $val) {
		$sum += $val;
	}
	$avg = $sum / count($args);
	return $avg;
}
