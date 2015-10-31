<!--This module will be used by scientists to subscribe to and unsubscribe from sensor data. 
It allows a user to list all sensors, their types, their locations, and their descriptions. 
The user is also able see which sensor he or she is currently subscribed to, 
and is able to add or remove subscriptions to sensors.
-->
<html>
	<body>

        <h1>Subscription Module</h1>
		<?php
			//Form information taken from http://www.w3schools.com/html/html_forms.asp
			//Shows all the sensor data
			include("/compsci/webdocs/msumner/web_docs/PHPconnectionDB.php");
			//establish connection
			$conn = connect();

			//sql collect all values from sensors
			$sql = 'SELECT * FROM sensors';

			//Prepare sql using conn and returns the statement identifier
			$stid = oci_parse($conn, $sql);

			//Execute a statement returned from oci_parse()
			$res = oci_execute($stid);

			//if error, retrieve the error using the oci_error() function & output an error
			if (!$res) {
				$err = oci_error($stid);
				echo htmlentities($err['message']);
			} else { echo 'Rows Extracted <br/>'; }

			if(isset($_POST['sensor']) && $_POST['sensor'] == 'Subscribed')	
			{
				echo $_Post['sensor'];
			}
			else
			{
				echo "Not Subscribed";
			}

			//Display results
			echo "<table>";
		    while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
		   		echo "<tr><td>" . $row['name'] . "</td><td>" . $row['age'] . "</td></tr>";
				foreach ($row as $item) {
					echo $item.'&nbsp;';
				}
			echo '</table>';
		    }

			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		?>
			<!--Add a Subscription-->
			<h2>Add a Subscription</h2>
         		<form name="Subscriptions" method="post" action="subscribe.php">
				Sensor name: 
           		<input type="text" name = "addSensor">
				</br></br>
				<input type = "submit" value="Submit">
         		</form>

			<!--Remove a Subscription-->
	</body>
</html>		


