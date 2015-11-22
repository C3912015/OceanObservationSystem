
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
	<title>Data Analysis</title>
	<h1>Data Analysis</h1>
	<form method = "POST">
		<input type = "radio" name = 'date' value = "year" checked > Yearly
		<input type = "radio" name = 'date' value = "quarter" > Quarterly
		<input type = "radio" name = 'date' value = "month" > Monthly
		<input type = "radio" name = 'date' value = "week" > Weekly
		<input type = "radio" name = 'date' value = "day"  > Daily
		<br><br>
		Sensor ID: 
		<input type = "text" name = "sensor">
		Time Selection:
		<input type = "text" name = "time">
		<br><br>
		<input type = "submit" name = 'Cube' value = "Generalization (Roll Up)">
		<input type = "submit" name = 'DrillDown' value = "Specialization (Drill Down)">
	</form>
	
	<!--User Subscriptions-->
	<h2>Subscribed Scalar Sensors:</h2>
	<?php
		include("PHPconnectionDB.php");
		/*Shows user sensor data*/
		//establish connection
		$conn = connect();
		
		//person id			
		$pid = $_SESSION['pid'];
		//sql collect all values from sensors
		$sql = "SELECT s.sensor_id, s.location, s.sensor_type, sd.date_created, s.description 
				FROM sensors s, subscriptions sc, scalar_data sd
				WHERE s.sensor_id = sc.sensor_id
				AND sc.person_id = {$pid}
				AND sd.sensor_id = sc.sensor_id
				AND s.sensor_type = 's'";

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
		echo "<th>Date Created</th>";
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

		/*Shows all the sensor data*/
		$sensor = 0;
		$choice = "year";
		
		/********************ROLLUP********************/
		if (isset($_POST['Cube'])){
			$choice = $_POST['date'];
			$sensor = $_POST['sensor'];
			//Get quarter and week: https://community.oracle.com/thread/1096637
			if ($choice == "year"){
				$query = "extract(year from sd.date_created)";
			}
			
			if ($choice == "quarter"){
				$query = "to_char(sd.date_created, 'YYYY-Q')";
			}

			if ($choice == "month"){
				$query = "extract(month from sd.date_created)";
			}

			if ($choice == "week"){
				$query = "to_char(to_date(sd.date_created, 'dd-mm-yyyy'), 'iw')";
			}
	
			if ($choice == "day"){
				$query = "extract(day from sd.date_created)";
			}
			echo "<h2>Cube Analysis</h2>";
			if ($sensor == ''){
				$sensor = 0;
			}

			$conn = connect();
			$pid = $_SESSION['pid'];

			//sql collect all values from sensors

			$sql = "SELECT sd.sensor_id, {$query}, 
				s.location, avg(sd.value), max(sd.value), min(sd.value)
				FROM scalar_data sd, subscriptions sc, sensors s
				WHERE sc.person_id = {$pid}
				AND sd.sensor_id = {$sensor}
				AND sd.sensor_id = sc.sensor_id
				AND s.sensor_id = sd.sensor_id
				GROUP BY CUBE(sd.sensor_id, {$query}, s.location)";

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
			$count = 0;
			echo "<table>";	
			while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
				foreach ($row as $item) {
					if (is_numeric($item)){
						echo round($item,2).'&nbsp;';
					}
					else{
						echo $item.'&nbsp;';
					}
				}
				if ($count == 0) {
					echo '</table>';
				}
				else {
					echo '</table><br/>';
				}
				$count += 1;
			}
		}
	
		
		/********************DRILLDOWN********************/
		if (isset($_POST['DrillDown'])){
			$choice = $_POST['date'];
			$sensor = $_POST['sensor'];
			$time = $_POST['time'];
			echo "<h2>Drilldown Analysis</h2>";
			if ($sensor == ''){
				$sensor = 0;
			}
			//Get quarter and week: https://community.oracle.com/thread/1096637
			if ($choice == "year"){
				$sql = "SELECT sd.sensor_id, extract(year from sd.date_created), 
				to_char(sd.date_created,'YYYY-Q'),
			
				$group = "to_char(extract (year from sd.date_created)) = {$time}";
				$query = "extract(year from sd.date_created), to_char(sd.date_created, 'YYYY-Q')";
			}
			
			if ($choice == "quarter"){
				$group = "to_char(sd.date_created, 'YYYY-Q') = 'YYYY'{$time}";
				$query = "to_char(sd.date_created, 'YYYY-Q')";
			}

			if ($choice == "month"){
				$group = "to_char(extract (month from sd.date_created)) = {$time}";
				$query = "extract(month from sd.date_created)";
			}

			if ($choice == "week"){
				$query = "to_char(to_date(sd.date_created, 'dd-mm-yyyy'), 'iw')";
			}
	
			if ($choice == "day"){
				$group = "to_char(extract ({$choice} from sd.date_created)) = {$time}";
				$query = "extract(day from sd.date_created)";
			}
				
			//establish connection
			$conn = connect();
			$pid = $_SESSION['pid'];
			//extract datepart: 
			//http://stackoverflow.com/questions/12155974/oracle-datepart-must-be-defined
			
			//sql collect all values from sensors
			$sql = "SELECT sd.sensor_id, {$query}, 
					s.location, avg(sd.value), max(sd.value), min(sd.value)
					FROM scalar_data sd, subscriptions sc, sensors s
					WHERE sc.person_id = {$pid}
					AND sd.sensor_id = {$sensor}
					AND sd.sensor_id = sc.sensor_id
					AND s.sensor_id = sd.sensor_id
					AND {$group};
					GROUP BY (sd.sensor_id, {$query}, s.location)";

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
			$count = 0;
			echo "<table>";	
			while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
				foreach ($row as $item) {
					if (is_numeric($item)){
						echo round($item,2).'&nbsp;';
					}
					else{
						echo $item.'&nbsp;';
					}
				}
				if ($count == 0) {
					echo '</table>';
				}
				else {
					echo '</table><br/>';
				}
				$count += 1;
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

</html>
