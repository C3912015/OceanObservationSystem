<!--This module will be used by scientists to subscribe to and unsubscribe from sensor data. 
It allows a user to list all sensors, their types, their locations, and their descriptions. 
The user is also able see which sensor he or she is currently subscribed to, 
and is able to add or remove subscriptions to sensors.

Resources
Insert PHP form into SQL: http://stackoverflow.com/questions/7105406/insert-into-database-table-from-form-not-working-sql
Form information: http://www.w3schools.com/html/html_forms.asp
-->
<html>
	<body>
		<title>Subscriptions</title>
        <h1>Subscription Module</h1>
		<h2>List of Sensors:</h2>
		<?php
			/*Shows all the sensor data*/
			include("../PHPconnectionDB.php");
			//establish connection
			$conn = connect();

			//sql collect all values from sensors
			$sql = 'SELECT * FROM sensors s';

			//Prepare sql using conn and returns the statement identifier
			$stid = oci_parse($conn, $sql);

			//Execute a statement returned from oci_parse()
			$res = oci_execute($stid);

			/*if error, retrieve the error using the oci_error() function 
			& output an error*/
			if (!$res) {
				$err = oci_error($stid);
				echo htmlentities($err['message']);
			}

			//Display results
			$count = 0;
			echo "<table>";
		    while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
				foreach ($row as $item) {
					echo $item.'&nbsp;';
				}
				if ($count == 0) {
					echo '</table>';
				}
				else {
					echo '</table><br/>';
					}
				$count += 1;
		    }

			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		?>
			<!--User Subscriptions-->
			<h2>List of User's Sensors:</h2>

		<?php
			/*Shows user sensor data*/
			//establish connection
			$conn = connect();

			//sql collect all values from sensors
			$sql = 'SELECT * FROM sensors s, subscriptions sc
					WHERE s.sensor_id = sc.sensor_id
					AND sc.person_id = 2';

			//Prepare sql using conn and returns the statement identifier
			$stid = oci_parse($conn, $sql);

			//Execute a statement returned from oci_parse()
			$res = oci_execute($stid);

			/*if error, retrieve the error using the oci_error() function 
			& output an error*/
			if (!$res) {
				$err = oci_error($stid);
				echo htmlentities($err['message']);
			}

			//Display results
			$count = 0;
			echo "<table>";
		    while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
				foreach ($row as $item) {
					echo $item.'&nbsp;';
				}
				if ($count == 0) {
					echo '</table>';
				}
				else {
					echo '</table><br/>';
					}
				$count += 1;
		    }

			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		?>

		<!--Add a Subscription-->
		<h2>Add a Subscription</h2>
         	<form name="Subscriptions" method="post" 					
			action="subscribe.php">
			Sensor ID: 
           	<input type="text" name = "addSensor">
			</br></br>
			<input type = "submit" value="Add Subscription">
         	</form>		
		<?php
			/*Display Table with added Subscription*/
			
			//establish connection
			$conn = connect();
			if ($_POST["addSensor"])
			{
				$addSensor = $_POST["addSensor"];
			
				//sql collect all values from sensors
				$sql = "INSERT INTO subscriptions Values({$addSensor}, 2)";

				//Prepare sql using conn and returns the statement identifier
				$stid = oci_parse($conn, $sql);

				//Execute a statement returned from oci_parse()
				$res = oci_execute($stid);

				/*if error, retrieve the error using the oci_error() function
				& output an error*/
				if (!$res) {
					$err = oci_error($stid);
					echo htmlentities($err['message']);
				}
				header("Refresh:0");
			}
		?>
		
			<!--Remove a Subscription-->
			<h2>Remove a Subscription</h2>
         		<form name="Subscriptions" method="post" action="subscribe.php">
				Sensor ID: 
           		<input type="text" name = "removeSensor">
				</br></br>
				<input type = "submit" value="Remove Subscription">
         		</form>
		<?php
			/*Display Table with removed subscription*/
			
			//establish connection
			$conn = connect();
			if($conn == FALSE)
			{
    			echo 'Cannot connect to database' . mysql_error();
			}

			if ($_POST["removeSensor"]){
				$removeSensor = $_POST['removeSensor'];
			
				//sql collect all values from sensors
				$sql = "DELETE FROM subscriptions 
						WHERE person_id = 2 
						AND sensor_id = ({$removeSensor})";

				/*Prepare sql using conn and returns the 
				statement identifier*/
				$stid = oci_parse($conn, $sql);

				//Execute a statement returned from oci_parse()
				$res = oci_execute($stid);

				/*if error, retrieve the error using the oci_error() 
				function & output an error*/
				if (!$res) {
					$err = oci_error($stid);
					echo htmlentities($err['message']);
				}
			header("Refresh:0");
			} 
		?>
	</body>
</html>		


