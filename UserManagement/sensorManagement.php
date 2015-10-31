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
          $sensor_ID = $_POST['RSensorID'];
          //check in DB
       
          // Remove from DB
          $sqlDEL = "DELETE FROM sensors WHERE sensor_id
          ={$sensor_ID};";
          echo $sqlDEL;
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
