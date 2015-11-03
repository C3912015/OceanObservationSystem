<!-- Add/Remove Sensor -->
<!-- add trims -->

<html>
  <body>
    <?php
       //connection function
       include("../../PHPconnectionDB.php");

       //establish connection
       $conn = connect();
       
       // Delete the sensor
       if($_POST["RemoveSensor"]){
          $sensor_ID = $_POST['RSensorID'];
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
             if($count > 0){
                $sqlDEL = "DELETE FROM sensors 
			WHERE sensor_id={$sensor_ID}";
                $rmSensor = oci_parse($conn, $sqlDEL);
                $res = oci_execute($rmSensor);
                echo 'Sensor removed';
             }else{echo "Unable to remove nonexistant sensor.";}
          oci_free_statement($sensorExist);
          } else { echo "No sensor given"; }
       }

       //Add the sensor
       if($_POST["AddSensor"]){
          echo('yo');
       }
       echo
       '<p><a href="http://consort.cs.ualberta.ca/~olexson/OceanObservationSystem/UserManagement/sensorManagement.html">Go
       Back</a>';
       oci_close($conn);
    ?>
  </body>
</html>
