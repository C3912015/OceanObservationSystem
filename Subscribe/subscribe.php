<!--This module will be used by scientists to subscribe to and unsubscribe from sensor data. 
It allows a user to list all sensors, their types, their locations, and their descriptions. 
The user is also able see which sensor he or she is currently subscribed to, 
and is able to add or remove subscriptions to sensors.
-->
<html>
	<body>

        <h1>Subscription Module</h1>
		<h2>List of Sensors:</h2>
		<?php
			/*Shows all the sensor data*/
			//Form information implemented from http://www.w3schools.com/html/html_forms.asp
			include("/compsci/webdocs/msumner/web_docs/PHPconnectionDB.php");
			//establish connection
			$conn = connect();

			//sql collect all values from sensors
			$sql = 'SELECT * FROM sensors s,subscriptions sc where s.sensor_id = sc.sensor_id';

			//Prepare sql using conn and returns the statement identifier
			$stid = oci_parse($conn, $sql);

			//Execute a statement returned from oci_parse()
			$res = oci_execute($stid);

			//if error, retrieve the error using the oci_error() function & output an error
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
         		<form name="Subscriptions" method="post" action="subscribe.php">
				Sensor ID: 
           		<input type="text" name = "addSensor">
				</br></br>
				<input type = "submit" value="Add Subscription">
         		</form>
				
		<?php
			/*Display Table with added Subscription*/
			
			//establish connection
			$conn = connect();
			$addSensor = $_POST['addSensor'];
			//sql collect all values from sensors
			$sql = 'INSERT INTO subscriptions Values({$addSensor},1)';

			//Prepare sql using conn and returns the statement identifier
			$stid = oci_parse($conn, $sql);

			//Execute a statement returned from oci_parse()
			$res = oci_execute($stid);

			//if error, retrieve the error using the oci_error() function & output an error
			if (!$res) {
				$err = oci_error($stid);
				echo htmlentities($err['message']);
			} else { echo 'Rows Extracted <br/>'; }
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

			//sql collect all values from sensors
			$sql = 'INSERT INTO subscriptions Values(addSensor,1)';

			//Prepare sql using conn and returns the statement identifier
			$stid = oci_parse($conn, $sql);

			//Execute a statement returned from oci_parse()
			$res = oci_execute($stid);

			//if error, retrieve the error using the oci_error() function & output an error
			if (!$res) {
				$err = oci_error($stid);
				echo htmlentities($err['message']);
			} else { echo 'Rows Extracted <br/>'; }
		?>
	</body>
</html>		


