<?php
	
	/***************************************************
	The Dynamic Drudge Template by Josh Cunningham v1.0
	Last update: 1/17/2011
	Contact: josh@joshcanhelp.com
	Site: http://www.joshcanhelp.com/drudge-report-website-template
	***************************************************/
	
	$configFile = 'config.txt';
	
	if (!file_exists($configFile)) {
		
		include 'configPage.php';
		
	}
	
	$fh = fopen( $configFile, 'r') or die ('Cannot open file!');	
	$configData = fread($fh, filesize($configFile));
	
	$configData = explode('"', $configData);
	
	$feedName = $configData[1];
	$pageName = $configData[3];
	$headLogo = $configData[5];
	$showAuthor = $configData[7];
	$showUpdate = $configData[9];
	$showSheetName = $configData[11];
	$newWindow = $configData[13];
	
	// Loads the RSS reader function
	$objDOM = new DOMDocument();
	
	// If the URL to your Google spreadsheet is invalid, the script dies and says why
	@$objDOM->load($feedName) or die("Invalid feed!");
	
	$note = $objDOM->getElementsByTagName("item");
	
	// Setting the variable that will eventually hold the entire page to be output
	$theHeader1 = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">';
	$theHeader1 .= '
	<html>
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
		<link rel="stylesheet" href="main.css" type="text/css" media="screen">
		<title>' . $pageName . '</title>';
	
	// file to include scripts and other meta information
	$file1 = 'includeMeta.php';
	
	if (file_exists($file1)){
		@ $fh = fopen($file1, 'r');
		@ $data = fread($fh, filesize($file1)) or die('Could not read' . $file1 . '!');
		fclose($fh); 
	
		$theHeader1 .= $data;
	}
	
	echo $theHeader1;
	
	$theHeader2 .= '
	</head>
	<body>';
			
	// file to include content at the top of the page
	$file2 = 'includeTop.php';
	
	if (file_exists($file2)){
		@ $fh = fopen($file2, 'r');
		@ $data = fread($fh, filesize($file2)) or die('Could not read' . $file2 . '!');
		fclose($fh); 
		
		$theHeader2 .= 
		'<div id="top-banner">'
		. $data .
		'</div>';
	}

	$theHeader2 .= '
		<div id="header">';
			
	
	if ($headLogo != '') {
		$theHeader2 .= '
			<h1 id="page-name">' . $pageName . '</h1>
			<div id="page-banner">
				<img src="' . $headLogo . '" alt="' . $pageName . '">
			</div>';
	} else {
		$theHeader2 .= '<h1>' . $pageName . '</h1>';	
	}

	// Set row counters
	$j = 2;
	$currRow = 3;
	$lastRow = 3;
	
	// Iterating through the RSS feed to store all possible URLs and current page content
	foreach ($note as $value)
	{	
		$cells = $value->getElementsByTagName("title");
		$currCell  = $cells->item(0)->nodeValue;	
		
		// Uses top-most row for top story information
		if ($currCell == 'A2') {
			$data = $value->getElementsByTagName("description");
			$topStory['link'] = $data->item(0)->nodeValue;
		}
		
		if ($currCell == 'B2') {
			$data = $value->getElementsByTagName("description");
			$topStory['text'] = $data->item(0)->nodeValue;
		}
		
		if ($currCell == 'C2') {
			$data = $value->getElementsByTagName("description");
			$topStory['desc'] = $data->item(0)->nodeValue;
		}
		
		if ($currCell == 'D2') {
			$data = $value->getElementsByTagName("description");
			$topStory['img'] = $data->item(0)->nodeValue;
		}
		
		$currCol = substr($currCell, 0, 1);
		
		$lastRow = $currRow;
		$currRow = substr($currCell, 1, 3);
		
		if ($currRow > 2) {
		
			if ($lastRow != $currRow) {
				$j++;
			}
			
			// Column 1 links and information
			if ($currCol == 'A') {
				$data = $value->getElementsByTagName("description");
				$colLink[1][$j]  = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'B') {
				$data = $value->getElementsByTagName("description");
				$colText[1][$j] = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'C') {
				$data = $value->getElementsByTagName("description");
				$colDesc[1][$j]  = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'D') {
				$data = $value->getElementsByTagName("description");
				$colImg[1][$j]  = $data->item(0)->nodeValue;
			}
			
			// Column 2 links and information
			if ($currCol == 'F') {
				$data = $value->getElementsByTagName("description");
				$colLink[2][$j]  = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'G') {
				$data = $value->getElementsByTagName("description");
				$colText[2][$j] = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'H') {
				$data = $value->getElementsByTagName("description");
				$colDesc[2][$j]  = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'I') {
				$data = $value->getElementsByTagName("description");
				$colImg[2][$j]  = $data->item(0)->nodeValue;
			}
			
			// Column 3 links and information
			if ($currCol == 'K') {
				$data = $value->getElementsByTagName("description");
				$colLink[3][$j]  = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'L') {
				$data = $value->getElementsByTagName("description");
				$colText[3][$j] = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'M') {
				$data = $value->getElementsByTagName("description");
				$colDesc[3][$j]  = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'N') {
				$data = $value->getElementsByTagName("description");
				$colImg[3][$j]  = $data->item(0)->nodeValue;
			}
		}
		
	} // end of feed iteration
	
	if (isset($topStory['link'])) {
		$theHeader2 .= '
			<h2><a href="' . $topStory['link'] . '" title="Story of the day">';
			
		if(isset($topStory['text'])) {
			$theHeader2 .= $topStory['text'];
		}
		
		$theHeader2 .= '</a></h2>';
		
		if (isset($topStory['img'])) {
			$theHeader2 .= '
			<a href="' . $topStory['link'] . '" title="Story of the day"><img src="' . $topStory['img'] . '" class="storyotd" alt=""></a>';
		}
		
		if (isset($topStory['desc'])) {
			$theHeader2 .= '
			<p>' . $topStory['desc'] . '</p>';
		}
		
	}
	$theHeader2 .= '
		</div>';
		
	echo $theHeader2;
	
	// If the config option to open links in a new window is set to "yes" then we want to inject the target="_blank" attribute
	if ($newWindow == 'yes') {
		$linkExtra = ' target="_blank"';
	} else {
		$linkExtra = '';
	}
	
	$x = 1;
	
	ksort($colLink);

	
	foreach($colLink as $col) {
	
		$column = '
			<div id="column' . $x . '">
				<ul>';
				
		for ($i = 3; $i <= $j; $i++) {
	
			if (isset($col[$i])) {
			
				$first7 = substr($col[$i], 0, 7);
				
				if ($first7 == 'http://' || $first7 == 'https:/' || $first7 == 'mailto:') {			
					$column .= '
					<li><a href="' . $col[$i] . '"' . $linkExtra . '>';
						
					if (isset($colText[$x][$i])) {
						$column .= $colText[$x][$i];
					} else {
						$column .= $col[$i];
					}
					
					$column .= '</a>';
					
					if (isset($colImg[$x][$i])) {
					$column .= '<p><a href="' . $col[$i] . '" class="img"' . $linkExtra . '><img src="' . $colImg[$x][$i] . '" alt="' . $colText[$x][$i] . '"></a></p>';
					}
				
					if (isset($colDesc[$x][$i])) {
						$column .= '<p>' . $colDesc[$x][$i] . '</p>';
					}
				
					$column .= '</li>';
					
				} elseif ($first7 == 'break') {			
					$column .= '
				<li>
				<hr>
				</li>';
				} else {			
					$column .= '
				<li>
				<h3>' . $colLink[$x][$i] . '</h3>
				</li>';
				}
				
			}
		}
		
		$column .= '
				</ul>
			</div>';
		
		echo $column;
		
		$x++;
	
	}

	
	// file to include content at the bottom of the page
	$file3 = 'includeBottom.php';
	
	if (file_exists($file3)){
		@ $fh = fopen($file3, 'r');
		@ $data = fread($fh, filesize($file3)) or die('Could not read' . $file3 . '!');
		fclose($fh); 
		
		$theFooter .= '
		<div id="bottom-banner">' 
		. $data . 
		'</div>';
	}
	
	if ($showAuthor == 'yes' || $showUpdate == 'yes' || $showDocName == 'yes')
	{
		$theFooter .= '
		<div id="theFooter">
			<p>';
		
		if ($showAuthor == 'yes')
		{
			$notes = $objDOM->getElementsByTagName("managingEditor");
			$author  = $notes->item(0)->nodeValue;			
			
			$authPieces = explode(' ', $author);
			$author = $authPieces[0];
			
			$theFooter .= "<span><strong>Site author:</strong> $author</span>";
		}
		
		if ($showUpdate == 'yes')
		{
			$notes = $objDOM->getElementsByTagName("lastBuildDate");
			$update  = $notes->item(0)->nodeValue;

			$update = substr($update, 0, 22);
			
			$theFooter .= "<span><strong>Last site update:</strong> $update GMT</span>";
		}
		
		if ($showSheetName == 'yes')
		{
			$notes = $objDOM->getElementsByTagName("title");
			$sheetName  = $notes->item(0)->nodeValue;			
			
			$theFooter .= "<span><strong>Document title:</strong> $sheetName</span>";
		}
				
		$theFooter .= '
				</p>
			</div>';
	}
		
	// file to include scripts at the bottom of the page like Google Analytics
	
	$file4 = 'includeScripts.php';
	
	if (file_exists($file4)){
		@ $fh = fopen($file4, 'r');
		@ $data = fread($fh, filesize($file4)) or die('Could not read' . $file4 . '!');
		fclose($fh); 
		
		$theFooter .= $data;
	}
		
	$theFooter .= '</body>
</html>';

	echo $theFooter;	

?>