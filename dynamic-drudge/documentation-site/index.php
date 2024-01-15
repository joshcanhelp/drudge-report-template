<?php
	
	/***************************************************
	The Premium Google Docs CMS by Josh Cunningham v1.0
	Last update: 2/10/2010
	Contact: josh@joshcanhelp.com
	Information: http://www.joshcanhelp.com/google-docs-cms/
	***************************************************/
	
		$configFile = 'config.txt';
	
	if (!file_exists($configFile)) {
		
		include 'configPage.php';
		
	}
	
	$fh = fopen( $configFile, 'r') or die ('Cannot open file!');	
	$configData = fread($fh, filesize($configFile));
	
	$configData = explode('"', $configData);
	
	$feedName = $configData[1];
	$siteName = $configData[3];
	$headLogo = $configData[5];
	$imageLocal = $configData[7];
	$showAuthor = $configData[9];
	$showUpdate = $configData[11];
	$showSheetName = $configData[13];
	
	// Loads the RSS reader function
	$objDOM = new DOMDocument();
	
	// If the URL to your Google spreadsheet is invalid, the script dies and says why
	@$objDOM->load($feedName) or die("Invalid feed!");
	
	// Check for a page name and, if there is none, the home page indicator is activated
	// If there is a page query, this information is stored to determine what page to display
	if ($_GET["page"] == '' || !$_GET["page"])
	{
		$pageRequest = "home";
		
	} else
	{
		$query = $_GET["page"];
		$pageRequest = $query;
	}
	
	$note = $objDOM->getElementsByTagName("item");
	
	$currentPage = false;
	
	// Setting the variable that will eventually hold the entire page to be output
	$theHeader = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">';
	$theHeader .= '
	<html>
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
		<link rel="stylesheet" href="main.css" type="text/css" media="screen" />';
		
	$theContent = '
						<div id="theContent">';
	
	// Setting the counters to store all the page links and page names
	// Links
	$i = 0;
	
	// Names
	$j = 0;
	
	// Setting 404 indicator
	$is404 = true;
		
	// Iterating through the RSS feed to store all possible URLs and current page content
	foreach ($note as $value)
	{	
		$cells = $value->getElementsByTagName("title");
		$currCell  = $cells->item(0)->nodeValue;	
		
		$currCol = $currCell[0];
		$currRow = '';
		
		$a = 1;
		
		while ($a <= strlen($currCell)) {
			if (preg_match('~^[A-Za-z]+$~', $currCell[$a]) > 0) {
				$currCol .= $currCell[$a];
			} else {
				$currRow .= $currCell[$a];
			}
			$a++;
		} 	
		
		if ($currCol == 'A')
		{
			$data = $value->getElementsByTagName("description");
			$pageLink  = $data->item(0)->nodeValue;
			
			$allLinks[$i] = $pageLink;
			$i ++;
			
			if ($pageLink == $pageRequest)
			{
				$currentPage = true;
				$is404 = false;
			} else
			{
				$currentPage = false;
			}

		}
		
		if ($currCol == 'B' && $currentPage == true)
		{
			$data = $value->getElementsByTagName("description");
			$pageTitle  = $data->item(0)->nodeValue;
			
			$theHeader .= '
	<title>' . $pageTitle . '</title>';
		}
		
		if ($currCol == 'C')
		{
			$data = $value->getElementsByTagName("description");
			$pageName  = $data->item(0)->nodeValue;
			
			$allPages[$j] = $pageName;
			$j ++;
			
			if ($currentPage == true)
			{
				$data = $value->getElementsByTagName("description");
				$pageHead = $data->item(0)->nodeValue;
				
				$theContent .= '
			<h1>' . $pageHead . '</h1>';
			}

		}
	
		if (($currCol == 'D' || $type == 'next') && $currentPage == true)
		{
			$data = $value->getElementsByTagName("description");
			$type = $data->item(0)->nodeValue;
			
			if ($type == 'd')
			{
				$theContent .= '
				<div>';
			} elseif ($type == 'p')
			{
				$theContent .= '
				<p>';
			} elseif ($type == 'h')
			{	
				$theContent .= '
				<h2>';
			} elseif ($type == 'a')
			{	
				$theContent .= '
				<p class="link"><a href="';
			} elseif ($type == 'i')
			{	
				$theContent .= '
				<p class="image"><img src="';
				
				if ($imageLocal != 'yes')
				{
					$theContent .= 'images/';
				}
			} elseif ($type == 'q')
			{	
				$theContent .= '
				<blockquote>';
			} elseif ($type == 'c')
			{	
				$theContent .= '
				<pre>';
			}
			
			$contentCheck = null;
		}
			
		if (($type == 'd' || $type == 'p' || $type == 'h' || $type == 'a' || $type == 'i' || $type == 'c' || $type == 'q') && $contentCheck == true && $currentPage == true)
		{
			$data = $value->getElementsByTagName("description");
			$content = $data->item(0)->nodeValue;
			
			$theContent .= $content;
			
			if ($type == 'd')
			{
				$theContent .= '
				</div>';
			}if ($type == 'p')
			{
				$theContent .= '</p>';
			} elseif ($type == 'h')
			{	
				$theContent .= '</h2>';
			} elseif ($type == 'a')
			{	
				$theContent .= '">' . $content . '</a></p>';
			} elseif ($type == 'i')
			{	
				$theContent .= '"></p>';
			} elseif ($type == 'q')
			{	
				$theContent .= '
				</blockquote>';
			} elseif ($type == 'c')
			{	
				$theContent .= '
				</pre>';
			}
			
			$contentCheck = false;
			$type = 'next';
			
		}
		
		if ($contentCheck == null)
		{
			$contentCheck = true;
		}
		
	} // end of feed iteration
	
		// file to include scripts and other meta information
	$file1 = 'includeMeta.php';
	
	if (file_exists($file1)) {
		@ $fh = fopen($file1, 'r');
		@ $data = fread($fh, filesize($file1)) or die('Could not read' . $file1 . '!');
		fclose($fh); 
	
		$theHeader .= $data;
	}
	
	$theHeader .= '
	</head>
	<body>
		<div id="theWrapper">		
		';
		
	// file to include content at the top of the page
	$file2 = 'includeTop.php';
	
	if (file_exists($file2)){
		@ $fh = fopen($file2, 'r');
		@ $data = fread($fh, filesize($file2)) or die('Could not read' . $file2 . '!');
		fclose($fh); 
		
		$theHeader .= 
		'<div id="top-banner">'
		. $data .
		'</div>';
	}
	
	$theHeader .= '
			<div id="theHeader">';
	
	if ($headLogo != '')
	{
		$theHeader .= '
				<a href="' . $_SERVER['PHP_SELF'] . '" title="Go home"><img src="images/' . $headLogo . '" alt="' . $siteName . '" /></a>';
	} elseif ($siteName != '') {
		$theHeader .= '<p>' . $siteName . '</p>';	
	}
	
	$theHeader .= '
			</div>';
	
	$theNav = '
			<ul id="theNav">';
	
	
	foreach ($allLinks as $oneLink)
	{
		$linkText = str_replace('-', ' ',$oneLink);
		$theNav .= '
				<li><a';
		
		if  ($pageRequest == $oneLink) {
			$theNav .= ' class="active" ';
		}
		
		$theNav .= ' href="?page=' . $oneLink . '">' . $linkText . '</a></li>';
	}		
	
	$theNav .= '
			</ul>';	
			
	$theContent .= '
			</div>';
	
	// file to include content at the bottom of the page
	$file3 = 'includeBottom.php';
	
	if (file_exists($file3)){
		@ $fh = fopen($file3, 'r');
		@ $data = fread($fh, filesize($file3)) or die('Could not read' . $file3 . '!');
		fclose($fh); 
		
		$theContent .= '
		<div id="bottom-banner">' 
		. $data . 
		'</div>';
	}
	
	if ($showAuthor = 'yes' || $showUpdate = 'yes' || $showSheetName = 'yes')
	{
		$theFooter = '
			<div id="theFooter">
				<p>';
		
		if ($showAuthor = 'yes')
		{
			$notes = $objDOM->getElementsByTagName("managingEditor");
			$author  = $notes->item(0)->nodeValue;			
			
			$authPieces = explode(' ', $author);
			$author = $authPieces[0];
			
			$theFooter .= "<strong>Site author:</strong> $author ";
		}
		
		if ($showUpdate = 'yes')
		{
			$notes = $objDOM->getElementsByTagName("lastBuildDate");
			$update  = $notes->item(0)->nodeValue;

			$update = substr($update, 0, 22);
			
			$theFooter .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Last site update:</strong> $update GMT";
		}
		
		if ($showSheetName = 'yes')
		{
			$notes = $objDOM->getElementsByTagName("title");
			$sheetName  = $notes->item(0)->nodeValue;			
			
			$theFooter .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Document title:</strong> $sheetName";
		}
				
		$theFooter .= '
				</p>
			</div>';
	}
	
	$theFooter .= '
		</div>';
		
		// file to include scripts at the bottom of the page like Google Analytics
	
	$file4 = 'includeScripts.php';
	
	if (file_exists($file4)){
		@ $fh = fopen($file4, 'r');
		@ $data = fread($fh, filesize($file4)) or die('Could not read' . $file4 . '!');
		fclose($fh); 
		
		$theFooter .= $data;
	}
	
	$theFooter .= '
	</body>
</html>';
	
	$the404 = '
			<div id="theContent">
				<h1>404 - page does not exist</h1>
				<p>Sorry, that page does not exist. All the pages on this site are listed on the left.</p>
			</div>';

	echo $theHeader;
	echo $theNav;
	
	if ($is404)
	{
		echo $the404;
	} else
	{
		echo $theContent;
	}
	echo $theFooter;
	

?>