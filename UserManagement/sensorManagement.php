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
          echo('Hey');
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
