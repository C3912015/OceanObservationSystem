<!-- Add/Remove Sensor -->

<html>
  <body>
    <?php
       //connection function
       include("../../PHPconnectionDB.php");

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
            echo '<p><a href="http://consort.cs.ualberta.ca/~olexson/OceanObservationSystem/UserManagement/sensorManagement.html">Go Back</a>';
            exit;
         }
         //if(isset($_POST['sLocation'])){}
         //if(isset($_POST['sType'])){}
         //if(isset($_POST['sDesc'])){}
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
       '<p><a href="http://consort.cs.ualberta.ca/~olexson/OceanObservationSystem/UserManagement/sensorManagement.html">Go
       Back</a>';
       oci_close($conn);
    ?>
  </body>
</html>
