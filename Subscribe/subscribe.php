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
			
			//sql add values into sensors
			$sql1 = 'INSERT INTO sensors VALUES (122)'; 

			//sql collect all values from sensors
			$sql2 = 'SELECT * FROM sensors';

			//Prepare sql using conn and returns the statement identifier
			$stid1 = oci_parse($conn, $sql1);
			$stid2 = oci_parse($conn, $sql2);

			//Execute a statement returned from oci_parse()
			$res1 = oci_execute($stid1);
			$res2 = oci_execute($stid2);
			//if error, retrieve the error using the oci_error() function & output an error
			if (!$res1) {
				$err = oci_error($stid1);
				echo htmlentities($err['message']);
			} else { echo 'Rows Extracted <br/>'; }


			//Display results
		   while ($row = oci_fetch_array($stid2, OCI_ASSOC)) {
		   	
			foreach ($row as $item) {
				echo $item.'&nbsp;';
			}
			echo '<br/>';
		   }

			// Free the statement identifier when closing the connection
			oci_free_statement($stid1);
			oci_free_statement($stid2);
			oci_close($conn);
		?>
	</body>
</html>		


