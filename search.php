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
	<?php
	else:

            echo 'search';
	?>

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

            //TODO date form error handling

            //testing
            /*foreach($keyword_array as $value){
                echo $value. "<br>";
            }*/

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
                        $sensor_query .= " AND (location = '{$value}'";
                    }
                    else
                        $sensor_query .= " OR location = '{$value}'";
                }
                $sensor_query .= ") ";
            }

            //TODO date form error handling (check blank)

            
            $subscription_query = "sc.sensor_id = s.sensor_id AND sc.person_id = {$pid} ";
            //$subscription_query .= " AND (sc.sensor_id = a.sensor_id AND sc.sensor_id = i.sensor_id AND sc.sensor_id = sd.sensor_id)";

 
            $pattern = "/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}$/";
            if(!preg_match($pattern, $time_begin, $matches)){
                header("Location: login.php");
                exit;
            }
            if(!preg_match($pattern, $time_end, $matches)){
                header("Location: login.php");
                exit;
            }
            $time_end = strtotime($time_end);
            $time_end = strtotime("+1 day", $time_end);
            $time_end = date('d-M-y', $time_end);
            $time_begin = strtotime($time_begin);
            $time_begin = date('d-M-y', $time_begin);


            $date_query = " AND ((a.date_created >= '{$time_begin}' AND a.date_created < '{$time_end}')";
            $date_query .= " OR (i.date_created >= '{$time_begin}' AND i.date_created < '{$time_end}')";
            $date_query .= " OR (sd.date_created >= '{$time_begin}' AND sd.date_created < '{$time_end}'))";



            //Keyword query
            $query = " ";
            if($keyword_array[0]!=""){
                foreach($keyword_array as $value){
                    $query .= " AND (s.description LIKE '%$value%'";
                    $query .= " OR a.description LIKE '%$value%'";
                    $query .= " OR i.description LIKE '%$value%')";
                }
            }

            $query = $subscription_query.$sensor_query.$date_query.$query;
            //$query = $subscription_query.$sensor_query.$query;
            //$sql = " SELECT * FROM sensors s, subscriptions sc, audio_recordings a, images i, scalar_data sd WHERE $query ";
            $sql = " SELECT * 
                     FROM (((sensors s join audio_recordings a on s.sensor_id = a.sensor_id)
                                       join images i on s.sensor_id = i.sensor_id) 
                                       join scalar_data sd on s.sensor_id = sd.sensor_id), subscriptions sc 
                     WHERE $query ";
            echo $sql;
            
            include("./PHPconnectionDB.php");
            $conn = connect();
            $stid = oci_parse($conn, $sql );
            $res=oci_execute($stid);

            if(!$res){
	        $err = oci_error($stid);
	        echo htmlentities($err['message']);
                oci_close($conn);
                exit;
            }
             //echo "hello";
            while($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)){
                foreach ($row as $item){
                    echo "<br>";
                    echo $item;
                    //echo "item";
                }
            
            }
            oci_close($conn);
        }
        ?>
	
    </body>
</html>
