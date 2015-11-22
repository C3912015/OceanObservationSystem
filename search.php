<html>
    <body>
	<h1>Welcome to Ocean Observation System</h1>
        <?php         
        //session start
        session_name('Login');
        session_start();
        ?>

	<!--If not logged in-->
	<?php
	if(!$_SESSION['id']):
            header("Location: login.php");
            exit;
	?>

	<!--Currently logged in-->
	<?php else:?>
        <form name="search" method="post" action="./search.php">
            <input type="text" name="keywords" value="<?php echo $_POST['keywords']; ?>"/>Keyword(s)<br/>
            <input type="text" name="sensor_info" value="<?php echo $_POST['sensor_info']; ?>"/>Sensor Type and/or Location<br/>
            <input type="text" name="time_begin" value="<?php echo $_POST['time_begin']; ?>"/>-<input type="text" name="time_end" value="<?php echo $_POST['time_end']; ?>"/>Time Period (YYYY/MM/DD)<br/>
            <input type="submit" name="search" value="Search"/>
        </form>

        <!--Return to main page-->
        <p><a href="?main">Back to Main page</a></p>

        <!--Closing the IF-ELSE construct-->
	<?php endif;?>


        <?php
	if(isset($_GET['main'])){
	    header("Location: login.php");
	    exit;
	}

        if(isset($_POST['search'])){
            $keywords = $_POST['keywords'];
            $sensor_info = $_POST['sensor_info'];
            $time_begin = $_POST['time_begin'];
            $time_end = $_POST['time_end'];
            $role = $_SESSION['role'];
            $pid = $_SESSION['pid'];            

            $keyword_array = explode(" ", $keywords);
            $sensor_info_array = explode(" ", $sensor_info);

            //Sensor location and type query
            $sensor_query = " ";
            $sensor_location_array = array();
            $sensor_type_array = array();

            foreach($sensor_info_array as $value){
                switch($value){
                case "a":
                    $sensor_type_array[] = $value;
                    break;
                case "i":
                    $sensor_type_array[] = $value;
                    break;
                case "t":
                    $sensor_type_array[] = $value;
                    break;
                case "o":
                    $sensor_type_array[] = $value;
                    break;
                default:
                    if(!empty($value))
                        $sensor_location_array[] = $value;
                }
            }

            $i = 0;
            if(!empty($sensor_type_array)){
                foreach($sensor_type_array as $value){
                    if($i==0){
                        $i++;
                        $sensor_query .= " AND (s.sensor_type = '{$value}'";
                    }
                    else
                        $sensor_query .= " OR s.sensor_type = '{$value}'";
                }
                $sensor_query .= ") ";
            }

            $i = 0;
            if(!empty($sensor_location_array)){
                foreach($sensor_location_array as $value){
                    if($i==0){
                        $i++;
                        $sensor_query .= " AND (s.location LIKE '%$value%'";
                    }
                    else
                        $sensor_query .= " OR s.location LIKE '%$value%'";
                }
                $sensor_query .= ") ";
            }

            $subscription_query = "sc.sensor_id = s.sensor_id AND sc.person_id = {$pid} ";


 
            $pattern = "/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}$/";
            if(!preg_match($pattern, $time_begin, $matches)){
                header("Location: ./search.php");
                exit;
            }
            if(!preg_match($pattern, $time_end, $matches)){
                header("Location: ./search.php");
                exit;
            }
            $time_end = strtotime($time_end);
            $time_end = strtotime("+1 day", $time_end);
            $time_end = date('d-M-y', $time_end);
            $time_begin = strtotime($time_begin);
            $time_begin = date('d-M-y', $time_begin);

            include("./PHPconnectionDB.php");
            $conn = connect();

            //Result display
            echo "<table border='1' cellspacing=1 width=\"100%\">";
			echo "<tr>";
			echo "<th>Sensor ID</th>";
			echo "<th>Sensor type</th>";
			echo "<th>Sensor Description</th>";
			echo "<th>Location</th>";
			echo "<th>Record ID</th>";
			echo "<th>Record Type</th>";
			echo "<th>Record Description</th>";
			echo "<th>Record Date Created</th>";
			echo "<th>Record download</th>";
			echo "</tr>";

            $keyword_query = " ";
            $i = 0;
            $j = 0;
            $empty_result_set = true; //flag for no results returned

            //If keyword found in description
            if($keyword_array[0]!=""){
                foreach($keyword_array as $value){
                    if($i==0){
                        $keyword_query .= " AND ((s.description LIKE '%$value%'";
                        $keyword_query .= " OR %record_type%.description LIKE '%$value%')";
                    }
                    else{
                        $keyword_query .= " AND (s.description LIKE '%$value%'";
                        $keyword_query .= " OR %record_type%.description LIKE '%$value%')";
                    }
                }
            }


            //check audio recording date created

            if($keyword_array[0]!=""){
                $date_query = " AND a.date_created >= '{$time_begin}' AND a.date_created < '{$time_end}' AND s.sensor_id = a.sensor_id)";
                $temp = str_replace("%record_type%", "a", $keyword_query);
                $query = $subscription_query.$sensor_query.$temp.$date_query;
            }
            else{
                $date_query = " AND a.date_created >= '{$time_begin}' AND a.date_created < '{$time_end}' AND s.sensor_id = a.sensor_id";
                $query = $subscription_query.$sensor_query.$date_query;
            }

            $sql = " SELECT s.sensor_id, s.sensor_type, s.description, s.location, a.recording_id, a.description AS a_description, a.date_created AS a_date_created
                     FROM sensors s, subscriptions sc, audio_recordings a
                     WHERE $query 
                     ORDER BY s.sensor_id";
            echo "<br>";
            echo $sql;
            $stid = oci_parse($conn, $sql);

            $res=oci_execute($stid);

            if(!$res){
            $err = oci_error($stid);
            echo htmlentities($err['message']);
                oci_free_statement($stid);
                oci_close($conn);
                exit;
            } 


            while(($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false){
                $empty_result_set = false;
                echo "<tr>";
                $j=0;
                foreach ($row as $item){
                    if($item!=null){
                    if($j==5){
                        echo "<td>"."Audio"."</td>\n";        
                    }
                        echo "<td>".$item."</td>\n";
                    }
                    else{
                        echo "<td>"."&nbsp;"."</td>\n";
                    }
                    $j = $j+1;
                    if($j==7){
                        echo "<td><a href='download.php?type=a&id=".$row['RECORDING_ID']."'>Download</a></td>\n";
                    }
                }
                echo "</tr>";
            }
              
            //check image date created
            if($keyword_array[0]!=""){
                $date_query = " AND i.date_created >= '{$time_begin}' AND i.date_created < '{$time_end}' AND s.sensor_id = i.sensor_id)";
                $temp = str_replace("%record_type%", "i", $keyword_query);
                $query = $subscription_query.$sensor_query.$temp.$date_query;
            }
            else{
                $date_query = " AND i.date_created >= '{$time_begin}' AND i.date_created < '{$time_end}' AND s.sensor_id = i.sensor_id";
                $query = $subscription_query.$sensor_query.$date_query;
            }

            $sql = " SELECT s.sensor_id, s.sensor_type, s.description, s.location, i.image_id, i.description AS i_description, i.date_created AS i_date_created
                     FROM sensors s, subscriptions sc, images i
                     WHERE $query 
                     ORDER BY s.sensor_id";
            $stid = oci_parse($conn, $sql);
            $res=oci_execute($stid);

            if(!$res){
            $err = oci_error($stid);
            echo htmlentities($err['message']);
                oci_free_statement($stid);
                oci_close($conn);
                exit;
            }

            while(($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false){
                $empty_result_set = false;
                echo "<tr>";
                $j = 0;
                foreach ($row as $item){
                    if($j==5){
                        echo "<td>"."Image"."</td>\n";        
                    }
                    if($item!=null){
                        echo "<td>".$item."</td>\n";
                    }
                    else{
                        echo "<td>"."&nbsp;"."</td>\n";
                    }
                    $j = $j+1;
                    if($j==7){
                        echo "<td><a href='download.php?type=i&id=".$row['IMAGE_ID']."'><img src='displayThumb.php?id=".$row['IMAGE_ID']."'/></a></td>\n";
                    }


                }
                echo "</tr>";
            }

            //check scalar date created

            if($keyword_array[0]!=""){
                $keyword_query = " ";
                $i = 0;
                foreach($keyword_array as $value){
                    if($i==0){
                        $keyword_query .= " AND (s.description LIKE '%$value%'";
                    }
                    else{
                        $keyword_query .= " AND s.description LIKE '%$value%'";
                    }
                }
            $date_query = " AND sd.date_created >= '{$time_begin}' AND sd.date_created < '{$time_end}' AND s.sensor_id = sd.sensor_id)";
            $query = $subscription_query.$sensor_query.$keyword_query.$date_query;
            }
            else{
                $date_query = " AND sd.date_created >= '{$time_begin}' AND sd.date_created < '{$time_end}' AND s.sensor_id = sd.sensor_id";
                $query = $subscription_query.$sensor_query.$date_query;
            }

            $sql = " SELECT s.sensor_id, s.sensor_type, s.description, s.location, sd.id, sd.date_created AS sd_date_created
                     FROM sensors s, subscriptions sc, scalar_data sd
                     WHERE $query 
                     ORDER BY s.sensor_id";
            echo "<br>";
            echo "<br>";
            echo $sql;
            $stid = oci_parse($conn, $sql);
            $res=oci_execute($stid);

            if(!$res){
            $err = oci_error($stid);
            echo htmlentities($err['message']);
                oci_free_statement($stid);
                oci_close($conn);
                exit;
            }

            while(($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false){
                $empty_result_set = false;
                echo "<tr>";
                $j=0;
                foreach ($row as $item){
                    if($j==5){
                        echo "<td>"."Scalar Data"."</td>\n";
                        echo "<td>"."&nbsp;"."</td>\n";
                        $j = $j+1;
                    }
                    if($item!=null){
                        echo "<td>".$item."</td>\n";
                    }
                    else{
                        echo "<td>"."&nbsp;"."</td>\n";
                    }
                    $j = $j+1;
                    if($j==7){
                        echo "<td><a href='download.php?type=s&id=".$row['ID']."'>Download</a></td>\n";
                    }
                }
                echo "</tr>";
            }


            echo "</table>";

            //No rows in the result set.
            if ($empty_result_set) {
                echo "No results found";
            }
            oci_free_statement($stid); 
            oci_close($conn);
        }

        ?>
	
    </body>
</html>
