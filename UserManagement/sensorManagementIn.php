<html>
   <body>
     <!-- Check if Sys Admin -->
     <h1> Remove a Sensor </h1>
      <!-- Sensor form -->
      <form  name = "addRmSensor" method = "post" action =
	    "sensorManagement.php"> </p>

	<!-- Remove a form -->
	Sensor ID: <input type = "text" name = "RSensorID"/> </p>

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
   </body>
</html>
	
