<?php


//look for directories called 'part1', 'part2', etc

if ($handle = opendir('.')) {
	$partDirs= array();	

	/* Loop through the directory */
	while (false !== ($entry = readdir($handle))) {
		if (false !== strpos($entry, 'part')) {
			$partDirs[] = $entry;
		}
	}
	
	closedir($handle);
}

asort($partDirs);

$partNum = 0;
$targetFiles = array();
foreach ($partDirs as $partDir) {
	
	echo "doing $partDir .... " . "\n";
	
	$partNum++;
	
	//list the files in a directory:
	$dirToOpen = './' . $partDir;
	
	if ($handle = opendir($dirToOpen)) {
		
		while (false !== ($entry = readdir($handle))) {
			if (strpos($entry, '_')) {
				//split filenames on the underscore
				list($prefix, $suffix) = explode('_', $entry);
				list($suffix, $extension) = explode ('.', $suffix);
				//if the number after the underscore has only one digit in it, rename the file so
				//the number has a zero in front of it	
				
				if ($suffix < 10) {
					$suffix = '00' . $suffix;
					$newFileName = $prefix . '_' . $suffix . '.pdf';
					$cmd = 'mv ./' . $partDir . '/' . $entry . ' ./' . $partDir . '/' . $newFileName;
					exec($cmd);
				
				} elseif ($suffix < 100) {
					$suffix = '0' . $suffix;
					$newFileName = $prefix . '_' . $suffix . '.pdf';
					$cmd = 'mv ./' . $partDir . '/' . $entry . ' ./' . $partDir . '/' . $newFileName;
					exec($cmd);					
				} 
				
			}		    	 	    
		}		
	
		closedir($handle);
		
		
		//read in the filenames again, 
		$handle = opendir($dirToOpen);
		$filesList = array();
		while (false !== ($entry = readdir($handle))) {
			if (strpos($entry, '.pdf')) {
				$filesList[] = $entry;	
				
			}
			
		}
		
		//sort ascending
		asort($filesList);
		
		//run the pdfunite command on the files in that order,
		$cmd = 'pdfunite ';
		
		foreach ($filesList as $file) {
			$cmd .= ' ./' . $partDir . '/' . $file;	
			
		}
		
		$targetFile = $prefix . '_part' . $partNum . '.pdf';
		
		$cmd .= ' ' . $targetFile;
		
		exec($cmd);
		
		$targetFiles[] = $targetFile;
		
	}
		
	
	
}

asort($targetFiles);


//when this has been completed for all the parts, combine the part files into one big file.

echo "combining all ... \n";

$cmd = 'pdfunite ';
foreach ($targetFiles as $fileToJoin) {
	$cmd .= ' ' . $fileToJoin;	
}
$cmd .= ' ' . $prefix . '.pdf';

exec($cmd);



?>
