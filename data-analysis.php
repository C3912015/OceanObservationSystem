
<?php 
	//Start session to get user id	
	session_name('Login');
	session_start();
	ob_start();
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
	?>
		
	<form method = 'POST'>
	<br><br>
	Sensor ID: 
	<input type = 'text' name = 'sensor1'>
	<br><br>
	<input type = 'submit' name = 'RollUp' value = 'Generalization (Roll Up)'>
	</form>
		
	<?php
	/********************ROLLUP********************/
	if (isset($_POST['RollUp'])){
		$sensor1 = (int)($_POST['sensor1']);
		echo "<h2>Rollup Analysis</h2>";

		//establish connection
		$conn = connect();
		$pid = $_SESSION['pid'];
		//extract datepart: 
		//http://stackoverflow.com/questions/12155974/oracle-datepart-must-be-defined
			
		/*This SQL statement takes a sensor ID and applies the rollup function on 
		year, quarter, month, week and day. It finishes by doing rollup on just 		the year and returns
		the min, max and avg. for these.*/
		$sql = "SELECT extract(year from sd.date_created),
				to_char(sd.date_created, 'YYYY-Q'),
				extract(month from sd.date_created),
				to_char(to_date(sd.date_created, 'dd-mm-yyyy'), 'w'),
				extract (day from sd.date_created),
				avg(sd.value), max(sd.value), min(sd.value)
				FROM scalar_data sd, subscriptions sc, sensors s
				WHERE sc.person_id = {$pid}
				AND sd.sensor_id = {$sensor1}
				AND sd.sensor_id = sc.sensor_id
				AND s.sensor_id = sd.sensor_id
				GROUP BY ROLLUP(extract(year from sd.date_created),
				to_char(sd.date_created, 'YYYY-Q'),
				extract(month from sd.date_created),
				to_char(to_date(sd.date_created, 'dd-mm-yyyy'), 'w'),
				extract (day from sd.date_created))";

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
		
	<h2>Drilldown Analysis</h2>
	<form method = 'POST'>
	Sensor ID:
	<input type = 'text' name = 'sensor'>
	*
	<br><br>
	Year: 
	<input type = 'text' name = 'year'>
	*
	<br><br>
	Quarter: 
	<select name = 'quarter'>
		<option value = 'null'></option>
		<option value = '1'>1</option>
		<option value = '2'>2</option>
		<option value = '3'>3</option>
		<option value = '4'>4</option>
	</select>
	<br><br>
	Month: 
	<select name = 'month'>
		<option value = 'null'></option>
		<option value = '1'>January</option>
		<option value = '2'>February</option>
		<option value = '3'>March</option>
		<option value = '4'>April</option>
		<option value = '5'>May</option>
		<option value = '6'>June</option>
		<option value = '7'>July</option>
		<option value = '8'>August</option>
		<option value = '9'>September</option>
		<option value = '10'>October</option>
		<option value = '11'>November</option>
		<option value = '12'>December</option>
	</select>
	<br><br>
	Week: 
	<select name = 'week'>
		<option value = 'null'></option>
		<option value = '1'>1</option>
		<option value = '2'>2</option>
		<option value = '3'>3</option>
		<option value = '4'>4</option>
		<option value = '5'>5</option>
	</select>
	<br><br>
	Day: 
		<select name = 'day'>
		<option value = 'null'></option>
		<option value = '1'>1</option>
		<option value = '2'>2</option>
		<option value = '3'>3</option>
		<option value = '4'>4</option>
		<option value = '5'>5</option>
		<option value = '6'>6</option>
		<option value = '7'>7</option>
		<option value = '8'>8</option>
		<option value = '9'>9</option>
		<option value = '10'>10</option>
		<option value = '11'>11</option>
		<option value = '12'>12</option>
		<option value = '13'>13</option>
		<option value = '14'>14</option>
		<option value = '15'>15</option>
		<option value = '16'>16</option>
		<option value = '17'>17</option>
		<option value = '18'>18</option>
		<option value = '19'>19</option>
		<option value = '20'>20</option>
		<option value = '21'>21</option>
		<option value = '22'>22</option>
		<option value = '23'>23</option>
		<option value = '24'>24</option>
		<option value = '25'>25</option>
		<option value = '26'>26</option>
		<option value = '27'>27</option>
		<option value = '28'>28</option>
		<option value = '29'>29</option>
		<option value = '30'>30</option>
		<option value = '31'>31</option>
	</select>
	<br><br>
	<input type = 'submit' name = 'drillDown' value = 'Specialization (Drill Down)'>
	</form>
		
	<?php
	/********************DRILLDOWN********************/
	//Get quarter and week: https://community.oracle.com/thread/1096637
	if (isset($_POST['drillDown'])){
		echo "<h2>Drilldown Analysis</h2>";
		$sensor = (int)($_POST['sensor']);
		$year = (int)($_POST['year']);
		$quarter = (int)$_POST['quarter'];
		$month = (int)$_POST['month'];
		$week = (int)$_POST['week'];
		$day = (int)$_POST['day'];

		$group = "extract(year from sd.date_created)";
		$query = "to_char(extract(year from sd.date_created)) = {$year}";
		
		$yearChar = (string)$year;
		$quarterChar = (string)$quarter;
		$quarterYear = $yearChar."-".$quarterChar; 
		if ($quarter != 0){
			$group = "to_char(sd.date_created, 'YYYY-Q')";
			if ($month != 0){
				$group = "extract(month from sd.date_created)";
				$query .= "AND extract(month from sd.date_created) = {$month}";
				if ($week != 0){
					$group = "to_char(sd.date_created, 'w')";
					$query .= "AND to_char(sd.date_created, 'w') = {$week}";
					if ($day != 0){
						$group = "extract (day from sd.date_created)";
						$query .= "AND extract(day from sd.date_created) = {$day}";
					}
				}
			}
		}

		//establish connection
		$conn = connect();
		$pid = $_SESSION['pid'];
		//extract datepart: 
		//http://stackoverflow.com/questions/12155974/oracle-datepart-must-be-defined
		//sql collect all values from sensors
		
		$sql = "SELECT sd.sensor_id, $group, s.location, 
				avg(sd.value), max(sd.value), min(sd.value)
				FROM scalar_data sd, subscriptions sc, sensors s
				WHERE sc.person_id = {$pid}
				AND sd.sensor_id = {$sensor}
				AND sd.sensor_id = sc.sensor_id
				AND s.sensor_id = sd.sensor_id
				AND {$query}
				GROUP BY (sd.sensor_id,{$group},s.location)";

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
