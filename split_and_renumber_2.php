<?php

/* reorder pages in a pdf where sections have been merged */

//number of sections we need to split
$number_of_parts = 2; 

//basename of the file to split
$filebase = '19090228';

//page to stop splitting
$pageStop = 16;

//part to put extra pages
$biggerPart = 1;


/* end of user configurable variables */


//split the pdf into individual pages
$filename = $filebase . '.pdf';

$cmd = 'pdfseparate ' . $filename . ' ' . $filebase . '_%d.pdf';

exec($cmd);

$cmd = 'mkdir orig';
exec ($cmd);

$cmd = 'mv ./' . $filename . ' ./orig/' . $filename;

exec ($cmd);


//make directories to put the sections in
for ($i = 1; $i <= $number_of_parts; $i++) {
	$dir = 'part' . $i;
	$cmd = 'mkdir ' . $dir;
	exec($cmd);	
	
}

if ($handle = opendir('.')) {
	$filesInDirectory = array();	

	/* Loop through the directory to get the filenames */
	while (false !== ($entry = readdir($handle))) {
		if (strpos($entry, '.pdf')) {
			$filesInDirectory[] = $entry;
		}		    	 	    
	}
	
	closedir($handle);
	$numberOfFiles = count($filesInDirectory);
	
	
	$part = 1;
	
	for ($i=1; $i<=$numberOfFiles; $i++) {
	
		$fileToMove = $filebase . '_' . $i . '.pdf';
		
		if ($i > $pageStop) {
			$part = $biggerPart;	
			
		} elseif ($part > $number_of_parts) {
			$part = 1;	
			
		}
		
		$cmd = 'mv ./' . $fileToMove . ' ./part' . $part . '/' . $fileToMove;
		
		exec($cmd);
		
		$part++;
	}
	
	
	
}






?>
