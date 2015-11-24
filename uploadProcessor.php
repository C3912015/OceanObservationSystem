<!-- http://www.htmlgoodies.com/beyond/php/article.php/3877766/Web-Developer-How-To-Upload-Images-Using-PHP.htm 
http://stackoverflow.com/questions/10456113/php-check-file-extension-in-upload-form
https://github.com/saemorris/TheRadSystem/blob/master/uploadProcessor.php
-->

<?php 
session_name('Login');
session_start(); ?>

<?php if($_SESSION['role']!='d'){
      header("Location:login.php");
      exit;
} ?>

<html>
<body>
<?php 
	//add check for right sensor type
	//connection function	
	include("PHPconnectionDB.php");

// make a note of the current working directory, relative to root. 
$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
echo $directory_self;
echo '</p>';


// make a note of the directory that will recieve the uploaded file 
$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'uploaded_files/'; 
echo $uploadsDirectory;
echo '</p>';

// make a note of the location of the upload form in case we need it 
$uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'upload.php'; 
echo $uploadForm;
echo '</p>';

// make a note of the location of the success page 
$uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'uploadSuccess.php'; 
//empty??

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

//echo $_FILES[$fieldname]['tmp_name'];

// make a unique filename for the uploaded file and check it is not already 
//if it is already taken keep trying until we find a vacant one 
//.$_FILES[$fieldname]['name']
$now = time(); 
while(file_exists($uploadFilename = $uploadsDirectory.$now.'-'.$_FILES[$fieldname]['tmp_name'])) 
{ 
    $now++; 
}


//get file extension
$fName = $_FILES[$fieldname]['name'];
$ext = pathinfo($fName, PATHINFO_EXTENSION);
$ext = strtolower($ext);

	//If scalar data .csv
	//scalar_data (id, sensor_id, date_created, value)
	if ($ext == "csv"){
		echo "CSV";
		//go through file
		$filePtr = fopen($_FILES[$fieldname]['tmp_name'],"r");
		$conn = connect();
		while (!feof($filePtr)){
			$data = fgetcsv($filePtr);
			if ($data != NULL){
				//print_r($data);
				$sensor_id = $data[0];
				$date = $data[1];
				$scalar = $data[2];
				//$conn = connect();
				$now++;
				$query = "INSERT INTO scalar_data VALUES ({$now},{$sensor_id},TO_DATE('{$date}','DD/MM/YYYY HH24:MI:SS'),{$scalar})";
				echo $query;
				$stmt = oci_parse($conn, $query);
				$res = oci_execute($stmt, OCI_DEFAULT);
			}
		}
		oci_commit($conn);
		oci_free_statement($stmt);
		oci_close($conn);
		//generate id and get info
		//query
	}
	//If image .jpg
	//images (image_id, sensor_id, date_created , 			recorded_data, thumbnail, description)
	elseif($ext == "jpg" or $ext=="jpeg"){
    		echo "JPG";
		//generate id
		
		//get info
		$sensor_id = $_POST['iSid'];
		$date = $_POST['iDate'];
		$desc = $_POST['iDesc'];

		//setupImage
		$imageUp = imagecreatefromjpeg($_FILES[$fieldname]['tmp_name']);
		$regX = imagesx($imageUp); //width
		$regY = imagesy($imageUp); //height
		$reg = imagecreatetruecolor($regX, $regY);
		imagecopyresampled($reg, $imageUp, 0, 0, 0, 0, $regX, $regY, 					$regX, $regY);
		
		//echo $_FILES[$fieldname]['tmp_name'];
		$name = $_FILES[$fieldname]['tmp_name'];
		$imagej = imagejpeg($reg, $name, 85);

		//echo "<img src = '{$reg}'>";

		//create thumbnail
		$tn = imagecreatetruecolor(150, 150);
		imagecopyresampled($tn, $imageUp, 0, 0, 0, 0, 150, 150, $regX, $regY);
		$tempfName = tempnam(sys_get_temp_dir(), "upthumb");
		imagejpeg($tn, $tempfName, 85);


		//query
		$conn = connect();
		//create lobs
		$image_lob = oci_new_descriptor($conn, OCI_D_LOB);
		$thumbnail_lob = oci_new_descriptor($conn, OCI_D_LOB);
		
		$sql= "INSERT INTO images (image_id, sensor_id, date_created , 			recoreded_data, thumbnail, description) VALUES({$now}, {$sensor_id}, TO_DATE('{$date}','DD/MM/YYYY HH24:MI:SS'), EMPTY_BLOB(), EMPTY_BLOB(), '{$desc}') RETURNING recoreded_data, thumbnail INTO :image, :thumbnail";

		echo $sql;
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":image", $image_lob, -1, OCI_B_BLOB);
		oci_bind_by_name($stmt, ":thumbnail", $thumbnail_lob, -1, OCI_B_BLOB);

		oci_execute($stmt, OCI_DEFAULT) or die ("Unable to execute query\n");

		if($image_lob->savefile($_FILES[$fieldname]['tmp_name'])){
			echo "image uploaded";
		} else { 
			oci_rollback($conn);
			echo "image unsuccessful";
		}

		if($thumbnail_lob->savefile($tempfName)){
			echo "thumbnail uploaded";
		} else { 
			oci_rollback($conn);
			echo "thumbnail unsuccessful";
		}
		
		oci_commit($conn);
		oci_free_statement($stmt);
		oci_close($conn);

		//test display image
		/*
		$connTest = connect();
		$query = "SELECT * FROM images WHERE image_id=1448225214";
		$stmt = oci_parse($connTest, $query);
		$res = oci_execute($stmt, OCI_DEFAULT);
    if($res){
        $row = oci_fetch_row($stmt);
		$thumb = OCIResult($stmt, "thumbnail");
		echo $thumb;
		//header('Content-type: application/octet-stream;');
		//header('Content-disposition: attachment;filename=test.jpeg');
	    header("Content-Type: image/jpeg");
            echo $thumb->load();
    }*/
		//header('Content-type:image/jpg');
		//readfile($fullpath);
		
	}
	//If audio .wav
	//audio_recordings (recording_id, sensor_id, date_created, length, recorded_data, description)
	elseif($ext == "wav"){
		echo "WAV";

		//get info
		$sensor_id = $_POST['aSid'];
		$date = $_POST['aDate'];
		$desc = $_POST['aDesc'];
		$len = $_POST['aLength'];

		//query
		$conn = connect();
		$wav_lob = oci_new_descriptor($conn, OCI_D_LOB);
		$sqlAudio= "INSERT INTO audio_recordings (recording_id, sensor_id, date_created , 			length, description, recorded_data) VALUES({$now}, {$sensor_id}, TO_DATE('{$date}','DD/MM/YYYY HH24:MI:SS'), {$len}, '{$desc}', EMPTY_BLOB()) RETURNING recorded_data INTO :wav";
		echo $sql;
		$stmtA = oci_parse($conn, $sqlAudio);
		oci_bind_by_name($stmtA, ":wav", $wav_lob,-1, OCI_B_BLOB);
		oci_execute($stmtA,OCI_NO_AUTO_COMMIT) or die("Unable to execute query");

		if($wav_lob->savefile($_FILES[$fieldname]['tmp_name'])){
			echo "wav uploaded";
		} else { 
			oci_rollback($conn);
			echo "wav unsuccessful";
		}		
	
		oci_commit($conn);
		oci_free_statement($stmtA);
		oci_close($conn);

	} else {
	//not the right format
		echo "not right format";
	exit;
	}



// If you got this far, everything has worked and the file has been successfully saved. 
// We are now going to redirect the client to a success page. 
header('Location: ' . $uploadSuccess); 


function error($error, $location, $seconds = 5) 
{ 
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"'."n". 
    '"http://www.w3.org/TR/html4/strict.dtd">'."nn". 
    '<html lang="en">'."n". 
    '    <head>'."n". 
    '        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">'."nn". 
    '        <link rel="stylesheet" type="text/css" href="stylesheet.css">'."nn". 
    '    <title>Upload error</title>'."nn". 
    '    </head>'."nn". 
    '    <body>'."nn". 
    '    <div id="Upload">'."nn". 
    '        <h1>Upload failure</h1>'."nn". 
    '        <p>An error has occurred: '."nn". 
    '        <span class="red">' . $error . '...</span>'."nn". 
    '         The upload form is reloading</p>'."nn". 
    '     </div>'."nn". 
    '</html>'; 
    exit; 
}


?> 
<a href="login.php">Back to Main Page</a>
</body>
</html>
