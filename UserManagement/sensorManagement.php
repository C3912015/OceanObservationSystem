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
       
          
          // Removee from DB
          $sqlDEL = "DELETE FROM sensors WHERE sensor_id
          ={$sensor_ID};";
          echo $sqlDEL;
          //$stid = oci_parse($conn, $sql);
          //$res = oci_execute($stid);
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
