<!--This module will be used by scientists to subscribe to and unsubscribe from sensor data. 
It allows a user to list all sensors, their types, their locations, and their descriptions. 
The user is also able see which sensor he or she is currently subscribed to, 
and is able to add or remove subscriptions to sensors.
-->

<html>
	<body>
		<?php
			//Shows all the sensor data
			include("PHPconnectionDB.php");
			//establish connection
			$conn = connect();

			//sql command
			$sql = 'SELECT * from sensors';

			//Prepare sql using conn and returns the statement identifier
			$stid = oci_parse($conn, $sql);

			//Execute a statement returned from oci_parse()
			$res = oci_execute($stid);

			//if error, retrieve the error using the oci_error() function & output an error
			if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			} else { echo 'Rows Extracted <br/>'; }


			//Display results
			while (($row = oci_fetch_array($stid, OCI_ASSOC))){
				foreach($row as $item){
					echo $item.'&nbsp;';
				}
				echo '<br/>';
			}

			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		?>
	</body>
</html>		


