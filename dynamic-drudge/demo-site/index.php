<?php
	
	/***************************************************
	The Dynamic Drudge Template by Josh Cunningham v1.0
	Last update: 1/31/2010
	Contact: josh@joshcanhelp.com
	Site: http://www.joshcanhelp.com/drudge-report-website-template
	***************************************************/
	
	$configFile = 'config.txt';
	
	if (!file_exists($configFile)) {
	
		if ( substr($_POST['rss'], 0, 37) != 'http://spreadsheets.google.com/feeds/' ) {
		
			$configPage = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
	<title>Dynamic Drudge Template config page</title>
	<link rel="stylesheet" href="main.css" type="text/css" media="screen">
</head>
<body>
<div id="configure">
<h1>Dynamic Drudge Template config page</h1>
<form action="' . $_SERVER['PHP_SELF'] . '" method="post" enctype="multipart/form-data">
	<p>
		<label for="rss">Enter the RSS feed for the Google Spreadsheet (required)</label>';
	
			if (isset($_POST['submitted'])) {
				$configPage .= '
		<span>Please input a valid Google Docs RSS feed</span>';
			}
	
    		$configPage .= '
		<input id="rss" name="rss" type="text" class="text" value="' . $_POST['rss'] . '"/>
	</p>
	
	<p>	
		<label for="pageTitle">Enter the title of this page (not required)</label>
		<input id="pageName" name="pageName" type="text" class="text" value="' . $_POST['pageName'] . '"/>
	</p>
	
	<p>	
	<label for="headLogo">Enter the URL to your header logo (not required)</label>
    <input id="headLogo" name="headLogo" type="text" class="text" value="' . $_POST['headLogo'] . '"/>
	</p>
	
	<p>	
	<label for="authEmail">Should the page show the author\'s email address?</label>
    <input id="authEmail" name="authEmail" type="checkbox" value="yes"';
			
			if ($_POST['authEmail'] == 'yes') {
				$configPage .= ' checked="checked"';
			}
			
			$configPage .= ' />
	</p>
	
	<p>	
	<label for="lastUpdate">Should the page show the last date it was changed?</label>
    <input id="lastUpdate" name="lastUpdate" type="checkbox" value="yes"';
			
			if ($_POST['lastUpdate'] == 'yes') {
				$configPage .= ' checked="checked"';
			}
			
			$configPage .= ' />
	</p>
	
	<p>	
	<label for="sheetName">Should the page show the Google Docs sheet name?</label>
    <input id="sheetName" name="sheetName" type="checkbox" value="yes"';
			
			if ($_POST['sheetName'] == 'yes') {
				$configPage .= ' checked="checked"';
			}
			
			$configPage .= ' />
	</p>
	
	<input type="hidden" name="submitted" value="yes" />
	<input type="submit" value="Save" />
</div>
</form>
</body>
</html>';
		
		echo $configPage;
		
		return;

		}

		$fh = fopen( $configFile, 'w') or die ('Cannot create file!');		
		
		$content = 'rss feed "';
		$content .= $_POST['rss'] . "\"\n";
		
		$content .= 'page name "';
		$content .= $_POST['pageName'] . "\"\n";
		
		$content .= 'logo image url "';
		$content .= $_POST['headLogo'] . "\"\n";
		
		$content .= 'show author email "';
		if ($_POST['authEmail'] == 'yes') {
			$content .= "yes\"\n"; 
		} else {
			$content .= "no\"\n"; 
		}
		
		$content .= 'show last change date "';
		if ($_POST['lastUpdate'] == 'yes') {
			$content .= "yes\"\n"; 
		} else {
			$content .= "no\"\n"; 
		}
		
		$content .= 'show sheet name "';
		if ($_POST['sheetName'] == 'yes') {
			$content .= "yes\"\n"; 
		} else {
			$content .= "no\"\n"; 
		}
		
		fwrite($fh, $content);
		fclose($fh);
		
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
		$currRow = substr($currCell, 1, 2);
		
		if ($currRow > 2) {
		
			if ($lastRow != $currRow) {
				$j++;
			}
			
			// Column 1 links and information
			if ($currCol == 'A') {
				$data = $value->getElementsByTagName("description");
				$col1Link[$j]  = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'B') {
				$data = $value->getElementsByTagName("description");
				$col1Text[$j] = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'C') {
				$data = $value->getElementsByTagName("description");
				$col1Desc[$j]  = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'D') {
				$data = $value->getElementsByTagName("description");
				$col1Img[$j]  = $data->item(0)->nodeValue;
			}
			
			// Column 2 links and information
			if ($currCol == 'F') {
				$data = $value->getElementsByTagName("description");
				$col2Link[$j]  = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'G') {
				$data = $value->getElementsByTagName("description");
				$col2Text[$j] = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'H') {
				$data = $value->getElementsByTagName("description");
				$col2Desc[$j]  = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'I') {
				$data = $value->getElementsByTagName("description");
				$col2Img[$j]  = $data->item(0)->nodeValue;
			}
			
			// Column 3 links and information
			if ($currCol == 'K') {
				$data = $value->getElementsByTagName("description");
				$col3Link[$j]  = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'L') {
				$data = $value->getElementsByTagName("description");
				$col3Text[$j] = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'M') {
				$data = $value->getElementsByTagName("description");
				$col3Desc[$j]  = $data->item(0)->nodeValue;
			}
			
			if ($currCol == 'N') {
				$data = $value->getElementsByTagName("description");
				$col3Img[$j]  = $data->item(0)->nodeValue;
			}
		}
		
	} // end of feed iteration
	
	if (isset($topStory['link'])) {
		$theHeader2 .= '
			<h2><a href="' . $topStory['link'] . '" title="Story of the day">Story of the day';
			
		if(isset($topStory['text'])) {
			$theHeader2 .= ': ' . $topStory['text'];
		}
		
		$theHeader2 .= ' &raquo</a></h2>';
		
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
	
	$column1 = '
		<div id="left-column">
			<ul>';
			
	for ($i = 3; $i <= $j; $i++) {

		if (isset($col1Link[$i])) {
		
			$first7 = substr($col1Link[$i], 0, 7);
			
			if ($first7 == 'http://') {			
				$column1 .= '
				<li><a href="' . $col1Link[$i] . '">';
					
				if (isset($col1Text[$i])) {
					$column1 .= $col1Text[$i];
				} else {
					$column1 .= $col1Link[$i];
				}
				
				$column1 .= '</a>';
				
				if (isset($col1Img[$i])) {
				$column1 .= '<p><a href="' . $col1Link[$i] . '" class="img"><img src="' . $col1Img[$i] . '" alt=""></a></p>';
				}
			
				if (isset($col1Desc[$i])) {
					$column1 .= '<p>' . $col1Desc[$i] . '</p>';
				}
			
				$column1 .= '</li>';
				
			} elseif ($first7 == 'break') {			
				$column1 .= '
			<li>
			<hr>
			</li>';
			} else {			
				$column1 .= '
			<li>
			<h3>' . $col1Link[$i] . '</h3>
			</li>';
			}
			
		}
	}
	
	$column1 .= '
			</ul>
		</div>';
	
	echo $column1;
	
	$column2 = '
		<div id="middle-column">
			<ul>';
	
	for ($i = 3; $i <= $j; $i++) {
	
		if (isset($col2Link[$i])) {
		
			$first7 = substr($col2Link[$i], 0, 7);
			
			if ($first7 == 'http://') {			
				$column2 .= '
				<li><a href="' . $col2Link[$i] . '">';
					
				if (isset($col2Text[$i])) {
					$column2 .= $col2Text[$i];
				} else {
					$column2 .= $col2Link[$i];
				}
				
				$column2 .= '</a>';
				
				if (isset($col2Img[$i])) {
				$column2 .= '<p><a href="' . $col2Link[$i] . '" class="img"><img src="' . $col2Img[$i] . '" alt=""></a></p>';
				}
			
				if (isset($col2Desc[$i])) {
					$column2 .= '<p>' . $col2Desc[$i] . '</p>';
				}
			
				$column2 .= '</li>';
				
			} elseif ($first7 == 'break') {			
				$column2 .= '
			<li>
			<hr>
			</li>';
			} else {			
				$column2 .= '
			<li>
			<h3>' . $col2Link[$i] . '</h3>
			</li>';
			}
			
		}
	}
	
	$column2 .= '
			</ul>
		</div>';
	
	echo $column2;
	
	$column3 = '
		<div id="right-column">
			<ul>';
	
	for ($i = 3; $i <= $j; $i++) {
	
		if (isset($col3Link[$i])) {
		
			$first7 = substr($col3Link[$i], 0, 7);
			
			if ($first7 == 'http://') {			
				$column3 .= '
				<li><a href="' . $col3Link[$i] . '">';
					
				if (isset($col3Text[$i])) {
					$column3 .= $col3Text[$i];
				} else {
					$column3 .= $col3Link[$i];
				}
				
				$column3 .= '</a>';
				
				if (isset($col3Img[$i])) {
				$column3 .= '<p><a href="' . $col3Link[$i] . '" class="img"><img src="' . $col3Img[$i] . '" alt="' . $col3Text[$i] . '"></a></p>';
				}
			
				if (isset($col3Desc[$i])) {
					$column3 .= '<p>' . $col3Desc[$i] . '</p>';
				}
			
				$column3 .= '</li>';
				
			} elseif ($first7 == 'break') {			
				$column3 .= '
			<li>
			<hr>
			</li>';
			} else {			
				$column3 .= '
			<li>
			<h3>' . $col3Link[$i] . '</h3>
			</li>';
			}
			
		}
	}
	
	$column3 .= '
			</ul>
		</div>';
		
	echo $column3;
	
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