<?php 
/* Make files consistent with downloaded files */

/* need to take files in directories 1858 through 1910 with names like
18581227.pdf and change them to 1858-12-27/dailycolonist18581227uvic.pdf */


for ($i = 1906; $i <=1910; $i++) {
	
	$directory = $i;
	
	$currentDir = "./$directory";
	
	//read contents of $directory
	
	if ($handle = opendir($currentDir)) {
		
	//echo "Directory handle: $handle\n";
	//echo "Entries:\n";
	$filesInDirectory = array();

	/* Loop through the directory to get the filenames */
	while (false !== ($entry = readdir($handle))) {
		if (substr_count($entry, '.pdf')) {
			$filesInDirectory[] = $entry;
		}		    	 	    
	}

	closedir($handle);
	
	//sort ascending
	asort ($filesInDirectory);
	
	
	//foreach file
	foreach ($filesInDirectory as $fileToMove) {
		
		echo $fileToMove . "\n";
	
	//chop off the extension (.pdf)
	
		list($base, $junk) = explode('.', $fileToMove);
	
	//get the component bits (year-month-day) from 18580131
	
		$year = substr($base, 0, 4);
		$month = substr($base, 4, 2);
		$day = substr($base, 6,2);
		
		$dirName = $year . '-' . $month . '-' . $day;
		
		echo "creating $dirName\n";
	
	
		//create a directory with the dashes in the right place
		
		$cmd = 'mkdir ' . $currentDir . '/' . $dirName;
		
		exec($cmd);
		
		sleep(1);
	
		//move the file from its current location into the directory and rename
		
		echo 'moving file to ' . $currentDir . '/' . $dirName . '/dailycolonist' . $base . 'uvic.pdf' . " \n---\n";
		
		$cmd = 'mv ' . $currentDir . '/' . $fileToMove . ' ' . $currentDir . '/' . $dirName . '/dailycolonist' . $base . 'uvic.pdf';
	
		exec ($cmd);
		
		sleep(2);
	
	}
	
	
}


}




?>
