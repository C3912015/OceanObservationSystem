<!-- http://www.htmlgoodies.com/beyond/php/article.php/3877766/Web-Developer-How-To-Upload-Images-Using-PHP.htm 
http://stackoverflow.com/questions/10456113/php-check-file-extension-in-upload-form
https://github.com/saemorris/TheRadSystem/blob/master/uploadProcessor.php
-->

<?php session_start(); ?>

<?php


// make a note of the current working directory, relative to root. 
$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 


// make a note of the directory that will recieve the uploaded file 
$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'uploaded_files/'; 

// make a note of the location of the upload form in case we need it 
$uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'upload.php'; 

// make a note of the location of the success page 
$uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'uploadSuccess.php'; 

// fieldname used within the file <input> of the HTML form 
$fieldname = 'file'; 

// possible PHP upload errors 
$errors = array(1 => 'php.ini max file size exceeded', 
                2 => 'html form max file size exceeded', 
                3 => 'file upload was only partial', 
                4 => 'no file was attached'); 

// check the upload form was actually submitted else print the form 
isset($_POST['submit']) 
    or error('the upload form is neaded', $uploadForm); 

// check for PHP's built-in uploading errors 
($_FILES[$fieldname]['error'] == 0) 
    or error($errors[$_FILES[$fieldname]['error']], $uploadForm); 

// check that the file we are working on really was the subject of an HTTP upload 
@is_uploaded_file($_FILES[$fieldname]['tmp_name']) 
    or error('not an HTTP upload', $uploadForm); 

// make a unique filename for the uploaded file and check it is not already 
//if it is already taken keep trying until we find a vacant one 
$now = time(); 
while(file_exists($uploadFilename = $uploadsDirectory.$now.'-'.$_FILES[$fieldname]['name'])) 
{ 
    $now++; 
}


/*
// now let's move the file to its final location and allocate the new filename to it 
@move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename) 
    or error('receiving directory insuffiecient permission', $uploadForm);
*/

//get file extension
$fileName = $_POST['file'];
$fName = $_FILES[$fieldname]['name'];
$ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
	//If scalar data .csv
	//scalar_data (id, sensor_id, date_created, value)
	if ($ext_== "csv"){
		echo "CSV";
	}
	//If image .jpg
	//images (image_id, sensor_id, date_created , 			recorded_data, thumbnail, description)
	elseif($ext == "jpg"){
    		echo "JPG";
	}
	//If audio .wav
	//audio_recordings (recording_id, sensor_id, date_created, 	length, recorded_data, description)
	elseif($ext == "wav"){
		echo "WAV";
	} else {
	//not the right format
		echo "not right format";
	exit;
	}



// If you got this far, everything has worked and the file has been successfully saved. 
// We are now going to redirect the client to a success page. 
//header('Location: ' . $uploadSuccess); 


// The following function is an error handler which is used 
// to output an HTML error page if the file upload fails 
function error($error, $location, $seconds = 5) 
{ 
   echo("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 " +
		"Transitional//EN\">\n" +
		"<HTML>\n" +
		"<HEAD><TITLE>Upload Message</TITLE></HEAD>\n" +
		"<BODY>\n" +
		"<H1>" +
	        response_message +
		"</H1>\n" +
		"</BODY></HTML>"); 
} // end error handler 

?> 
