<!--This module will be used by scientists to subscribe to and unsubscribe from sensor data. 
It allows a user to list all sensors, their types, their locations, and their descriptions. 
The user is also able see which sensor he or she is currently subscribed to, 
and is able to add or remove subscriptions to sensors.

Resources
Insert PHP form into SQL: http://stackoverflow.com/questions/7105406/insert-into-database-table-from-form-not-working-sql
Form information: http://www.w3schools.com/html/html_forms.asp
Refresh page: http://stackoverflow.com/questions/12383371/refresh-a-page-using-php
-->

<?php 
	//Start session to get user id	
	session_name('Login');
	session_start();
	$usr = $_SESSION['usr'];
	$role = $_SESSION['role'];
	if ($usr == '' or $role != 's'){
		header("Location: login.php");
		exit;
	}
?>


<html>
	<body>
	<title>Subscriptions</title>
        <h1>Subscription Module</h1>

	<h2>All Sensors:</h2>
		<?php
			/*Shows all the sensor data*/
			include("PHPconnectionDB.php");
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
				//echo htmlentities($err['message']);
			}

			//Display results
			echo "<table border='1' cellspacing=1 width=\"50%\">";
			echo "<tr>";
			echo "<th>Sensor ID</th>";
			echo "<th>Location</th>";
			echo "<th>Sensor Type</th>";
			echo "<th>Description</th>";
			echo "</tr>";
		    while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
				echo "<tr>";
				foreach ($row as $item) {
					echo "<td align = 'center'>".$item."</td>\n";
				}
				echo '</tr>';
		   	 }
			echo "</table>";

			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		?>

		<?php
			//User Subscriptions
			echo "<h2>Subscribed Sensors:</h2>";
			/*Shows user sensor data*/
			//establish connection
			$conn = connect();
			
			//person id			
			$pid = $_SESSION['pid'];
			//sql collect all values from sensors
			$sql = "SELECT s.sensor_id, s.location, s.sensor_type, s.description 
					FROM sensors s, subscriptions sc
					WHERE s.sensor_id = sc.sensor_id
					AND sc.person_id = {$pid}";

			//Prepare sql using conn and returns the statement identifier
			$stid = oci_parse($conn, $sql);

			//Execute a statement returned from oci_parse()
			$res = oci_execute($stid);

			/*if error, retrieve the error using the oci_error() function 
			& output an error*/
			if (!$res) {
				$err = oci_error($stid);
				//echo htmlentities($err['message']);
			}

			//Display results
			echo "<table border='1' cellspacing=1 width=\"50%\">";
			echo "<tr>";
			echo "<th>Sensor ID</th>";
			echo "<th>Location</th>";
			echo "<th>Sensor Type</th>";
			echo "<th>Description</th>";
			echo "</tr>";
			echo "<tr>";
		    while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
				foreach ($row as $item) {
					echo "<td align = 'center'>".$item.'&nbsp;'."</td>\n";
				}
				echo '</tr>';
		   	}
			echo "</table>";

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
				$sql = "INSERT INTO subscriptions Values({$addSensor}, {$pid})";

				//Prepare sql using conn and returns the statement identifier
				$stid = oci_parse($conn, $sql);

				//Execute a statement returned from oci_parse()
				
				if(@$res = oci_execute($stid)){
					header("Refresh:0");
				}

				/*if error, retrieve the error using the oci_error() function
				& output Invalid Subscription*/
				

				else{
					//$err = oci_error($stid);
					//echo htmlentities($err['message']);
					echo "Invalid Subscription ID";
				}
				
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
					WHERE person_id = $pid 
					AND sensor_id = ({$removeSensor})";

				/*Prepare sql using conn and returns the 
				statement identifier*/
				$stid = oci_parse($conn, $sql);

				//Execute a statement returned from oci_parse()
				if(@$res = oci_execute($stid)){
					header("Refresh:0");
				}

				/*if error, retrieve the error using the oci_error() 
				function & output an error*/
				else {
					//$err = oci_error($stid);
					//echo htmlentities($err['message']);
					echo "Invalid Subscription ID";
				}
				
			} 
		?>

       <!--Return to main page-->
        <p><a href="?main">Back to Main page</a></p>

        <?php
		if(isset($_GET['main'])){
	   		header("Location: login.php");
	    	exit;
		}
		?>

	</body>
</html>		


