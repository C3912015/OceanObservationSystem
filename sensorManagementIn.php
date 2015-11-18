<?php 
session_name('Login');
session_start(); ?>
<html>
   <body>.
     <?php if($_SESSION['role']!='a'){
	header("Location: login.php");
	exit;
     } ?>
     <?php
	include("PHPconnectionDB.php");
	$conn = connect();
	?>
     <!-- Check if Sys Admin -->
     <h1> Remove a Sensor </h1>
      <!-- Sensor form -->
      <form  name = "addRmSensor" method = "post" action =
	    "sensorManagement.php"> </p>

	<!-- Remove form -->
	Sensor ID: <input type = "text" name = "RSensorID"/> </p>
<!--
        <select>

	<?php
	/* Add drop down menu - incomplete
	   $sqlSensorExist = "select * from sensors 
				where sensor_id = {$sensor_ID}";
           $sensorExist = oci_parse($conn, $sqlSensorExist);
           $sExistRes = oci_execute($sensorExist);
	   while($row = oci_fetch_array($sensorExist, OCI_ASSOC)){
	     echo '<option value = "'$row[0]'">"$row[0]"</option>';
	   }
	   */
	?>
	</select> -->
	</p>
	<!-- remove button -->
	<input type = "submit" name = "RemoveSensor" value =
	"Remove Sensor"/>

	<!-- Add a sensor -->
	<h1> Add a Sensor </h1>
	Sensor ID: <input type = "text" name = "ASensorID"/> </p>
	Sensor Location: <input type = "text" name = "sLocation"/> </p>
	Sensor Type (a, i, s): <input type = "text" name =
	"sType"/> </p>
	Sensor Description: <input type = "text" name = "sDesc"/> </p>
	
	<!-- add button -->
	<input type = "submit" name = "AddSensor" value = "Add Sensor"/>

	</form>
	<a href = "login.php">Back to Main Page</a>
   </body>
</html>
	
