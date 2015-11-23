<!--This module will be used by data curators to upload images, 
audio recordings, and scalar measurements in batches,
after possible processing and annotations in the description field. 
Upload all the data stored in the user's local file system to the database. 
Curator can: 
  -upload scalar data in batches by csv file
  -upload image/audio
-->
<!-- http://www.htmlgoodies.com/beyond/php/article.php/3877766/Web-Developer-How-To-Upload-Images-Using-PHP.htm -->

<?php 
session_name('Login');
session_start(); ?>

<?php if($_SESSION['role']!='d'){
      header("Location:login.php");
      exit;
} ?>

    <?php
	//current working directory
    	$directory_self  = str_replace(basename($_SERVER['PHP_SELF']), '',$_SERVER['PHP_SELF']);

	//location of upload handler script
	$uploadHandler = 'http' . $_SERVER['HTTP_HOST'] . $director_self . 'uploadProcessor.php';
	$uploadHandler = "uploadProcessor.php";
    ?>
<html lang = "en">
	<head>
		<meta http-equiv = "content-type" content = "text/html; charset=iso-8859-1">
		<link rel = "stylesheet" type = "text/css" href "stylesheet.css">
		<title>Upload Form</title>
	</head>
	<body>
	<form id = "Upload" action = "uploadProcessor.php" enctype = "multipart/form-data" method = "post">
	<h2>Audio Information</h2>
		Sensor ID: <input type = "text" name = "aSid"/> </p>
		Date Created</br>(DD/MM/YYYY hh:mm:ss): <input type = "text" name = "aDate"/></p>
		Length: <input type = "text" name = "aLength"/></p>
		Description: <input type = "text" name = "aDesc"/><p>
		<h2>Image Information</h2>
		Sensor ID: <input type = "text" name = "iSid"/></p>
		Date Created</br>(DD/MM/YYYY hh:mm:ss) : <input type = "text" name = "iDate"/></p>
		Description: <input type = "text" name = "iDesc"/></p>
		
		<h3>Upload a File - jpg, wav, or csv </h3><p>
	<label for "file">File to upload:</label>
	<input id = "file" type = "file" name = "file"></p>
	<input id = "submit" type = "submit" name = "submit" value = "Upload"></p>

	</form>

<a href="login.php">Back to Main Page</a>
	</body>
</html>
