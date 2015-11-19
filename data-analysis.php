<html>
	<h1>Data Analysis</h1>
	<form>
		<input type = "radio" name = "date" value = "daily" checked > Daily
		<input type = "radio" name = "date" value = "weekly" > Weekly
		<input type = "radio" name = "date" value = "monthly" > Monthly
		<input type = "radio" name = "date" value = "quarterly" > Quarterly
		<input type = "radio" name = "date" value = "yearly" > Yearly
		<br><br>
		<input type = "submit" value = "Generalization (Rollup)">
		&nbsp;
		<input type = "submit" value = "Specialization (Drilldown)">
	</form>
	
	<?php
	/*Shows all the sensor data*/
		include("PHPconnectionDB.php");
		//establish connection
		$conn = connect();

		//sql collect all values from sensors
		$sql = "SELECT DATEPART(yyyy,date_created) AS OrderYear
				FROM scalar_data";

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
