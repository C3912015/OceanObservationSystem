<!-- Add/Remove Sensor -->
<?php 
session_name('Login');
session_start();?>
<?php if($_SESSION['role']!='a'){
	header("Location: login.php");
	exit;
} ?>
<html>
  <body>
    <?php
       //connection function
       include("PHPconnectionDB.php");

       //establish connection
       $conn = connect();
       
       // Delete the sensor
       if($_POST["RemoveSensor"]){
          $sensor_ID = trim($_POST['RSensorID']);
          $count = 0;

          //check if sensor exists in DB
          // if it does, remove it
          if($sensor_ID != NULL){
             $sqlSensorExist = "select * from sensors 
				where sensor_id = {$sensor_ID}";
             $sensorExist = oci_parse($conn, $sqlSensorExist);
             $sExistRes = oci_execute($sensorExist);

             while (($row = oci_fetch_array($sensorExist, OCI_ASSOC)))
             {
                $count++;
             }
             //sensor_id exists, so remove it
             if($count > 0){
		//remove from subscriptions
                $sqlDELsub = "DELETE FROM subscriptions 
			WHERE sensor_id={$sensor_ID}";
                $rmSensor = oci_parse($conn, $sqlDELsub);
		if($res = oci_execute($rmSensor)){
		}else{ //do nothing
		}
		
		// remove from audio_recordings
		$sqlDELaudio = "DELETE FROM audio_recordings 
			WHERE sensor_id={$sensor_ID}";
                $rmSensor = oci_parse($conn, $sqlDELaudio);
		if($res = oci_execute($rmSensor)){
		}else{ //do nothing
		}
		

		// remove from images
		$sqlDELimages = "DELETE FROM sensors 
			WHERE sensor_id={$sensor_ID}";
                $rmSensor = oci_parse($conn, $sqlDELimages);
		if($res = oci_execute($rmSensor)){
		}else{ //do nothing
		}

		// remove from scalar_data
		$sqlDELscalar = "DELETE FROM sensors 
			WHERE sensor_id={$sensor_ID}";
                $rmSensor = oci_parse($conn, $sqlDELscalar);
		if($res = oci_execute($rmSensor)){
		}else{ //do nothing
		}

		// remove from sensors		
                $sqlDEL = "DELETE FROM sensors 
			WHERE sensor_id={$sensor_ID}";
                $rmSensor = oci_parse($conn, $sqlDEL);
                $res = oci_execute($rmSensor);
                echo 'Sensor removed';
             //sensor doesn't exist
             }else{echo "Unable to remove nonexistant sensor.";}
          //no input given
          oci_free_statement($sensorExist);
          } else { echo "No sensor given"; }
       }

       //Add the sensor
       //check for null values ??
       if($_POST["AddSensor"]){
          //get values and check they are not null ??
         if($_POST['ASensorID'] != NULL){ $sensor_ID = $_POST['ASensorID']; 
         } else { 
            echo 'No sensor ID given'; 
            echo '<p><a href="sensorManagementIn.php">Go Back</a>';
            exit;
         }
         $location = $_POST['sLocation'];
         $sensorType = $_POST['sType'];
         $sensorDesc = $_POST['sDesc'];

         $sqlADD = "INSERT INTO sensors VALUES ({$sensor_ID},
         '{$location}', '{$sensorType}', '{$sensorDesc}')";
         echo $sqlADD;
         $addSensor = oci_parse($conn, $sqlADD);
         $res = oci_execute($addSensor);
       }
       echo
       '<p><a href="sensorManagementIn.php">Go
       Back</a>';
       oci_close($conn);
    ?>
    <p><a href="Login.php">Back to Main Page</a>';
  </body>
</html>
