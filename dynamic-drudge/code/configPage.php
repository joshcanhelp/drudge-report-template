<?php

$feedInput = $_POST['rss'];
		
$feedPieces = explode('/' , $feedInput);

if ($feedPieces[2] != 'spreadsheets.google.com' || $feedPieces[3] != 'feeds' || $feedPieces[4] != 'cells') { ?>
	
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
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <p>
                    <label for="rss">Enter the RSS feed for the Google Spreadsheet (required)</label><?php
                        if (isset($_POST['submitted'])) { ?>
                    <span>Please input a valid Google Docs RSS feed</span>
                        <?php } ?><input id="rss" name="rss" type="text" class="text" value="<?php echo $_POST['rss'] ?>"/>
                </p>
                
                <p>	
                    <label for="pageTitle">Enter the title of this page (not required)</label>
                    <input id="pageName" name="pageName" type="text" class="text" value="<?php echo $_POST['pageName'] ?>"/>
                </p>
                
                <p>	
                    <label for="headLogo">Enter the URL to your header logo (not required)</label>
                    <input id="headLogo" name="headLogo" type="text" class="text" value="<?php echo $_POST['headLogo'] ?>"/>
                </p>
                
                <p>	
                    <label for="authEmail">Should the page show the author's email address?</label>
                    <input id="authEmail" name="authEmail" type="checkbox" value="yes"<?php			
                        if ($_POST['authEmail'] == 'yes') {
                            $configPage .= ' checked="checked"';
                        } ?>/>
                </p>
                
                <p>	
                <label for="lastUpdate">Should the page show the last date it was changed?</label>
                <input id="lastUpdate" name="lastUpdate" type="checkbox" value="yes"<?php			
                        if ($_POST['lastUpdate'] == 'yes') {
                            $configPage .= ' checked="checked"';
                        } ?> />
                </p>
                
                <p>	
                <label for="sheetName">Should the page show the Google Docs sheet name?</label>
                <input id="sheetName" name="sheetName" type="checkbox" value="yes"<?php			
                        if ($_POST['sheetName'] == 'yes') {
                            $configPage .= ' checked="checked"';
                        } ?>/>
                </p>
                <p>	
                <label for="newWindow">Should the links open in a new window?</label>
                <input id="newWindow" name="newWindow" type="checkbox" value="yes"<?php
                        if ($_POST['newWindow'] == 'yes') {
                            $configPage .= ' checked="checked"';
                        } ?>/>
                </p>
                
                <input type="hidden" name="submitted" value="yes" />
                <input type="submit" value="Save" />
            </form>
        </div>
    </body>
</html>
	<?php
    
    die;

}

$fh = fopen( $configFile, 'w') or die ('Cannot create file!');		

$content = 'rss feed "';
$content .= $_POST['rss'] . "\"\r\n";

$content .= 'page name "';
$content .= $_POST['pageName'] . "\"\r\n";

$content .= 'logo image url "';
$content .= $_POST['headLogo'] . "\"\r\n";

$content .= 'show author email "';
if ($_POST['authEmail'] == 'yes') {
	$content .= "yes\"\n"; 
} else {
	$content .= "no\"\n"; 
}

$content .= 'show last change date "';
if ($_POST['lastUpdate'] == 'yes') {
	$content .= "yes\"\r\n"; 
} else {
	$content .= "no\"\r\n"; 
}

$content .= 'show sheet name "';
if ($_POST['sheetName'] == 'yes') {
	$content .= "yes\"\r\n"; 
} else {
	$content .= "no\"\r\n"; 
}

$content .= 'open links in new window "';
if ($_POST['newWindow'] == 'yes') {
	$content .= "yes\"\r\n"; 
} else {
	$content .= "no\"\r\n"; 
}

fwrite($fh, $content);
fclose($fh);