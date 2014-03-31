<?php
/* Find missing/skipped days in the run */


$dateBegin = '1910-7-1';
$dateEnd = '1920-12-31';

$dirPrefix = '/home/jdurno/Desktop/ColonistDownload';


list($yearBegin, $monthBegin, $dayBegin) = explode('-', $dateBegin);
list($yearEnd, $monthEnd, $dayEnd) = explode('-', $dateEnd);

$firstPass = TRUE;
$gap = 0;

for ($year = $yearBegin; $year <= $yearEnd; $year++) {
	
	
	if ($firstPass) {
		$month = $monthBegin;
	} else {
		$month = 1;
	}
		
	if ($year == $yearEnd) {
		$lastMonth = $monthEnd;	
	} else {
		$lastMonth = 12;
	}
	
	for ($month; $month <= $lastMonth; $month++ ) {
		$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		
		for ($day = 1; $day <= $numDays; $day++) {
			$dateString = $year . '-' . formatNumAsText($month) . '-' . formatNumAsText($day);
			
			$directory = $dirPrefix . '/' . $year . '/' . $dateString;
			
			
			if (!file_exists("$directory")) {
				if ($gap == 1) {
					echo $dateString . " Two day gap found at " . date('l d F, Y', strtotime($dateString)) . "\n";
					$gap = 0;
				} else {
					$gap++;	
				}
			} else {
				$gap = 0;	
			}
				
		}
		
		
		
	}
	
	$firstPass = FALSE;
}

echo "\nAll done\n";




function formatNumAsText($num) {
	if ($num < 10) {
		$num = "0$num";	
	}
	return $num;
}





?>
