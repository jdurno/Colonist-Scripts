<?php
//combine page pdfs into issues

/* iterate through a directory to find issues
/* directory pattern is year/month/day/ymd001.pdf ymd002.pdf etc
/* eg. /1858/12/11/18581211001.pdf                                 
*/                                               

$yearToCombine = '1886';               
$dirPrefix = '/home/jdurno/Projects/Colonist/IA-upload/items2combine/raid5/colonist';
$moveToPrefix = '/home/jdurno/Projects/Colonist/IA-upload/items2upload';

if (!file_exists("$dirPrefix/$yearToCombine")) {
	echo "Hey, what gives? That year ($yearToCombine) don't exist";	
}     

                                                        
for ($i=1;$i<=12;$i++) {
	//check months                                           
	$month = addLeadingZero($i);
	if (!file_exists("$dirPrefix/$yearToCombine/$month"))	{
		continue;	
	}
	//check days
	for ($j=1;$j<=31;$j++) {
		$day = addLeadingZero($j);
		if (!file_exists("$dirPrefix/$yearToCombine/$month/$day"))	{
			continue;	
		}	
		
		echo "Found something at $dirPrefix/$yearToCombine/$month/$day\n";
		$currentDir =  "$dirPrefix/$yearToCombine/$month/$day";    
		
		if ($handle = opendir($currentDir)) {
				
			//echo "Directory handle: $handle\n";
			//echo "Entries:\n";
			$filesInDirectory = array();
		
			/* Loop through the directory to get the filenames */
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					$filesInDirectory[] = $entry;
				}		    	 	    
			}
		
			closedir($handle);
			asort ($filesInDirectory);
			
			/* combine pages into issues
			/* can use: pdfunite in-1.pdf in-2.pdf in-n.pdf out.pdf
			*/

			$cmd = "pdfunite ";
		
			foreach ($filesInDirectory as $file) {
				$cmd .= "$currentDir/$file" . ' ';	
			}
			$outfile = $yearToCombine . $month . $day . '.pdf';   
			
			$cmd .=  "$currentDir/$outfile";
			echo $cmd . "\n\n";
			exec($cmd);
			sleep(2);
			$cmd = "mv $currentDir/$outfile $moveToPrefix/$outfile";
			echo $cmd . "\n";
			exec($cmd);
			sleep(2);
			echo "--\n";
			
			
		}
			
	}
	
	
	
}

function addLeadingZero($num) {
	$num = intval($num);
	if ($num < 10) {
		return '0' . $num;	
	} else {
		return $num;	
	}
	
}





//move to directory awaiting upload




?>
