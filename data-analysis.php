
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
		Time Selection:
		<input type = "text" name = "time">
		<br><br>
		Sensor ID: 
		<input type = "text" name = "sensor">
		<br><br>
		<input type = "submit" name = 'Cube' value = "Sensor Analysus(Cube)">
		<input type = "submit" name = 'RollUp' value = "Generalization(Roll Up)">
		<input type = "submit" name = 'DrillDown' value = "Specialization(Drill Down)">
	</form>
	
	<?php
		include("PHPconnectionDB.php");
		/*Shows all the sensor data*/
		$sensor = 0;
		$choice = "year";
		
		if (isset($_POST['Cube'])){
			$sensor = $_POST['sensor'];
			echo "<h2>Cube Analysis</h2>";
			if ($sensor == ''){
				$sensor = 0;
			}

			$conn = connect();
			$pid = $_SESSION['pid'];

			//sql collect all values from sensors
			$sql = "SELECT sd.sensor_id, sd.date_created, 
				s.location, avg(sd.value), max(sd.value), min(sd.value)
				FROM scalar_data sd, subscriptions sc, sensors s
				WHERE sc.person_id = {$pid}
				AND sd.sensor_id = {$sensor}
				AND sd.sensor_id = sc.sensor_id
				AND s.sensor_id = sd.sensor_id
				GROUP BY CUBE(sd.sensor_id, sd.date_created, s.location)";

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
	
		if (isset($_POST['RollUp'])){
			$choice = $_POST['date'];
			$sensor = $_POST['sensor'];
			echo "<h2>Rollup Analysis</h2>";
			if ($sensor == ''){
				$sensor = 0;
			}

			//establish connection
			$conn = connect();
			$pid = $_SESSION['pid'];
			//extract datepart: 
			//http://stackoverflow.com/questions/12155974/oracle-datepart-must-be-defined
			
			//sql collect all values from sensors
			$sql = "SELECT extract (day from sd.date_created),
					extract(month from sd.date_created),extract(year from sd.date_created),
					avg(sd.value), max(sd.value), min(sd.value)
					FROM scalar_data sd, subscriptions sc, sensors s
					WHERE sc.person_id = {$pid}
					AND sd.sensor_id = {$sensor}
					AND sd.sensor_id = sc.sensor_id
					AND s.sensor_id = sd.sensor_id
					GROUP BY ROLLUP(extract(day from sd.date_created),
					extract(month from sd.date_created),extract(year from sd.date_created))";

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

		if (isset($_POST['DrillDown'])){
			$choice = $_POST['date'];
			$sensor = $_POST['sensor'];
			echo "<h2>Drilldown Analysis</h2>";
			if ($sensor == ''){
				$sensor = 0;
			}

			//establish connection
			$conn = connect();
			$pid = $_SESSION['pid'];
			//extract datepart: 
			//http://stackoverflow.com/questions/12155974/oracle-datepart-must-be-defined

			//sql collect all values from sensors
			$sql = "SELECT sd.sensor_id,  extract({$choice} from sd.date_created), 
					s.location, avg(sd.value), max(sd.value), min(sd.value)
					FROM scalar_data sd, subscriptions sc, sensors s
					WHERE sc.person_id = {$pid}
					AND sd.sensor_id = {$sensor}
					AND sd.sensor_id = sc.sensor_id
					AND s.sensor_id = sd.sensor_id
					GROUP BY (sd.sensor_id, extract({$choice} from sd.date_created), s.location)";

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
