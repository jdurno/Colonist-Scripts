<?php
/* Download Colonist pdfs from the Internet Archive */


/* Read in file of identifiers and dates */
//$issuesList = 'issues-asof-20130920.txt';
$issuesList = 'issues-1910-1920-final.txt';
//$issuesList = 'test-issues.txt';

$issuesListAsString = file_get_contents($issuesList);

$issuesListAsString = trim($issuesListAsString, "\n");

/* Create an array */
$issuesListAsLines = explode("\n", $issuesListAsString);

$issuesList = array();

foreach ($issuesListAsLines as $line) {
	list($identifier, $title) = explode ("\t", $line);
	
	/* get the date by stripping away the junk we don't need from the title field */
	/* typically these come in like: The Daily Colonist (1910 -07-27) */
	$date = str_replace("The ", "", $title); //get rid of 'The '
	$date = str_replace(" ", "", $date); //get rid of spaces
	$date = str_replace("DailyColonist(", "", $date);
	$date = str_replace(")", "", $date);
	list ($year, $month, $day) = explode('-', $date);
	
	echo $year . " : " . $date . " : " . $identifier . "\n";

	/* Create the top-level 'year' directory, if we need one */

	if (!file_exists("$year")) {
	    mkdir("$year", 0777, true);
	}	

	
	
	/* Now we have the array, create a directory to stash the pdf, by date */
	
	if (!file_exists("$year/$date")) {
	    mkdir("$year/$date", 0777, true);
	} else {
		//note that directory already exists
		echo "directory already exists at: $year/$date.\n";
	}
	
	
	/* get the pdf, and stash it in the directory */
	/* links are like http://archive.org/download/dailycolonist53185uvic/dailycolonist53185uvic.pdf */
	
	$filename = $identifier . '.pdf';
	$url = 'http://archive.org/download/' . $identifier . '/' . $filename;
	
	echo $url . "\n";
	
	
	    
	$path = "/home/jdurno/Desktop/ColonistDownload/$year/$date/$filename";
	
	//only get the file if it doesn't already exist
	if (!file_exists($path)) {
		echo "getting " . $url . "\n\n";	
		$cmd = "wget -q \"$url\" -O $filename";
		exec($cmd);
		sleep(5);
		$cmd = "mv $filename $path";
		exec($cmd);
		sleep(5);
		
	} else {
		//don't get the file a second time
		echo "duplicate file at: $path. Skipping file.\n\n";
		continue;
	}
		

}

echo "\nAll done\n";


?>
