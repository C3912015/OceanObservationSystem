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

            $keyword_array = explode(" ", $keywords);
            $sensor_info_array = explode(" ", $sensor_info);

            $begin = new DateTime($time_begin);
            $end = new DateTime($time_end);
            $end = $end->modify('+1 day');
            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval ,$end);
            //TODO date form error handling

            //testing
            foreach($keyword_array as $value){
                echo $value. "<br>";
            }

            //TODO sensor form error handling (blank values)
            $sensor_query = " ";
            foreach($sensor_info_array as $value){
                switch($value){
                case "a":
                    $sensor_query .= " OR sensor_type = 'a' ";
                    break;
                case "i":
                    $sensor_query .= " OR sensor_type = 'i' ";
                    break;
                case "t":
                    $sensor_query .= " OR sensor_type = 't' ";
                    break;
                case "o":
                    $sensor_query .= " OR sensor_type = 'o' ";
                    break;
                default:
                    $sensor_query .= " OR location = '".$value."' ";
                }
                echo $value. "<br>";
            }

            
            foreach($daterange as $date){
                echo $date->format("Ymd") . "<br>";
            }
            //TODO date form error handling (check blank)

            //keyword
           // echo $sensor_query;


            $role_query = " role = '".$role."' ";
            $date_query = " AND date_created BETWEEN '".$begin->format("Ymd")."' AND '".$end->format("Ymd")."' ";
            //echo $role_query;
           // echo $date_query;

            $query = " ";
            foreach($keyword_array as $value){
                    $query .= "AND keywords LIKE '%$value%' ";
            }

            $query = $role_query.$sensor_query.$date_query.$query;
            $sql = " SELECT * FROM SEARCH_ENGINE WHERE $query ";
            echo $sql;
            //preg_match

        }
        ?>
	
    </body>
</html>
