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
            //TODO escape input
            $keywords = $_POST['keywords'];
            $sensor_info = $_POST['sensor_info'];
            $time_begin = $_POST['time_begin'];
            $time_end = $_POST['time_end'];
            $role = $_SESSION['role'];
            $pid = $_SESSION['pid'];            

            $keyword_array = explode(" ", $keywords);
            $sensor_info_array = explode(" ", $sensor_info);

            //$begin = new DateTime($time_begin);
            //$end = new DateTime($time_end);
            //$end = $end->modify('+1 day');
            //$interval = new DateInterval('P1D');
            //$daterange = new DatePeriod($begin, $interval ,$end);
            //TODO date form error handling

            //testing
            foreach($keyword_array as $value){
                echo $value. "<br>";
            }

            //Sensor location and type query
            $sensor_query = " ";
            $sensor_location_array = array();
            $sensor_type_array = array();

            foreach($sensor_info_array as $value){
                switch($value){
                case "a":
                    $sensor_type_array[] = $value;
                    break;
                case "a":
                    $sensor_type_array[] = $value;
                    break;
                case "a":
                    $sensor_type_array[] = $value;
                    break;
                case "a":
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
                        $sensor_query .= " AND sensor_type = {$value}";
                    }
                    else
                        $sensor_query .= " OR sensor_type = {$value}";
                }
            }

            $i = 0;
            if(!empty($sensor_location_array)){
                foreach($sensor_location_array as $value){
                    if($i==0){
                        $i++;
                        $sensor_query .= " AND location = {$value}";
                    }
                    else
                        $sensor_query .= " OR location = {$value}";
                }
            }

            
            /*foreach($daterange as $date){
                echo $date->format("Ymd") . "<br>";
            }*/
            //TODO date form error handling (check blank)

            
            $subscription_query = "s.sensor_id = sc.sensor_id AND sc.person_id = {$pid} ";
 
            //Date query
           // $begin =
           /* $temp = explode("/", $time_end);
            //$time_end_day = intval($temp[1])+1;
             function inc($matches) {
                 return ++$matches[1];
             }
            $time_end_day = preg_replace_callback( "|(\d+)|", "inc", $temp[1]);
            $time_end = "{$temp[0]}/{$time_end_day}/{$temp[2]}";
*/
            //$pattern = "/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/";
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
            $time_end = date('y/m/d', $time_end);

            $date_query = " AND (a.date_created >= '{$time_begin}' AND a.date_created < '{$time_end}')";
            $date_query .= " OR (i.date_created >= '{$time_begin}' AND i.date_created < '{$time_end}')";
            $date_query .= " OR (sd.date_created >= '{$time_begin}' AND sd.date_created < '{$time_end}')";
           /* $date_query = " AND date_created BETWEEN '".$begin->format("Ymd")."' AND '".$end->format("Ymd")."' ";*/


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
            $sql = " SELECT * FROM SEARCH_ENGINE WHERE $query ";
            echo $sql;

        }
        ?>
	
    </body>
</html>
