       <?php         
        //session start
        //session_name('Login');
        session_start();
        ?>
<html>
    <body>
	<h1>Welcome to Ocean Observation System</h1>
 
        <p>Hello <?php echo $_SESSION['usr'] ? $_SESSION['usr'] : 'Guest';?>!</p>

	<!--If not logged in-->
	<?php
	if(!$_SESSION['id']):
	?>
        <form name="login" method="post" action="">
            Username : <input type="text" name="user"/> <br/>
            Password : <input type="text" name="user_password" 		
			id="user_password"/><br/>
            <input type="submit" name="validate" value="Login"/>
        </form>

        <!--Output login errors, if any-->
	<?php
	if($_SESSION['msg']['login-err']){
		echo $_SESSION['msg']['login-err'];
		unset($_SESSION['msg']['login-err']);
	}
	?>

	<!--Currently logged in-->
	<?php else:?>

        <!--Admin-->
        <?php if($_SESSION['role']=='a'):?>
            <!--insert admin links here-->
        <?php endif;?>

        <!--Data curator-->
        <?php if($_SESSION['role']=='d'):?>
            <!--insert curator links here-->
        <?php endif;?>

        <!--Scientist-->
        <?php if($_SESSION['role']=='s'):?>
        	<p><a href="../Subscribe/subscribe.php"> View Subscriptions</a></p>
        <?php endif;?>

        <!--Search-->
        <form name="search" method="post" action="./search.php">
            <input type="text" name="keywords"/>Keyword(s)<br/>
            <input type="text" name="sensor_info"/>Sensor Type and/or Location<br/>
            <input type="text" name="time_begin" value="From"/>-<input type="text" name="time_end"
 value="To"/>Time Period (YYYY-MM-DD)<br/>
            <input type="submit" name="search" value="Search"/>
        </form>


        <!--Edit personal information-->
        <a href="./user-settings.php"> Change Personal Information</a>

        <!--Log off-->
        <p><a href="?logoff">Log off</a></p>

        <!--Closing the IF-ELSE construct-->
	<?php endif;?>
     

        <?php
        session_set_cookie_params(60*60*2);  // 2 hour session duration
	//If you are logged in, but you don't have a cookie (browser restart)
	if($_SESSION['id'] && !isset($_COOKIE['cookie'])){
            //Destroy the session
	    $_SESSION = array();
	    session_destroy();
	}

	//If you are logging off
	if(isset($_GET['logoff'])){
	    $_SESSION = array();
	    session_destroy();
	    header("Location: login.php");
	    exit;
	}

	//If you are logging on
	if($_POST['validate']=='Login'){
            unset($err);
	    $err = array();

	    if(!$_POST['user'] || !$_POST['user_password'])
	        $err[] = 'All the fields must be filled in';

	    if(!count($err)){
                include("../PHPconnectionDB.php");
                $conn = connect();
                //TODO Escaping all input data
                $USER=$_POST['user'];            		
                $USER_PASSWORD=$_POST['user_password'];  

                //sql command
                $sql = "SELECT * FROM users U WHERE U.user_name='$USER' AND U.password='$USER_PASSWORD'";
           
                //Prepare sql using conn and returns the statement identifier
                $stid = oci_parse($conn, $sql );
          
                //Execute a statement returned from oci_parse()
                $res=oci_execute($stid);

                //if execution error, retrieve the error using the oci_error() function & output an error
                if(!$res){
	            //$ocierr = oci_error($stid);
                    $err[]=oci_error($stid);
	            //echo htmlentities($err['message']);
                    oci_close($conn);
                    header("Location: login.php");
                    exit;
                }
            
                $row = oci_fetch($stid);
            
                //login successfull
                if($row){
                   //session variables
                   $_SESSION['usr'] = oci_result($stid, 'USER_NAME');
                   $_SESSION['id'] = session_id();
                   $_SESSION['pid'] = oci_result($stid, 'PERSON_ID');
                   $_SESSION['role'] = oci_result($stid, 'ROLE');
                   setcookie('cookie');
                   
                }
                else $err[]='Invalid username and/or password';
            }

            if($err)
                //Save the error messages in the session
                unset($_SESSION['msg']['login-err']);
	        $_SESSION['msg']['login-err'] = implode('<br />',$err);

            oci_close($conn);
	    header("Location: login.php");
	    exit;
	}
	?>
	<br/>
    </body>
</html>
