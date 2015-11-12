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
            include("./PHPconnectionDB.php");
            $conn = connect();
            $pid = $_SESSION['pid'];
            $sql = "SELECT * FROM persons P WHERE P.person_id='$pid'";
            $stid = oci_parse($conn, $sql );
            $res=oci_execute($stid);

            if(!$res){
	        $err = oci_error($stid);
	        //echo htmlentities($err['message']);
                oci_close($conn);
                header("Location: login.php");
                exit;
            }
            $row = oci_fetch($stid);

            $firstname = oci_result($stid, 'FIRST_NAME');
            $lastname = oci_result($stid, 'LAST_NAME');
            $address = oci_result($stid, 'ADDRESS');
            $email = oci_result($stid, 'EMAIL');
            $phone = oci_result($stid, 'PHONE');
            oci_close($conn);
            
	?>


        <form action="user-settings.php" method="post">
            <input type="text" name="firstname" value="<?php echo $firstname; ?>"/><label>First Name</label></br>
            <input type="text" name="lastname" value="<?php echo $lastname; ?>"/><label>Last Name</label></br>
            <input type="text" name="address" value="<?php echo $address; ?>"/><label>Address</label></br>
            <input type="text" name="email" value="<?php echo $email; ?>"/><label>Email</label></br>
            <input type="text" name="phone" value="<?php echo $phone; ?>"/><label>Phone</label></br>
            <input type="submit" name="edit" value="Edit" />
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

        if(isset($_POST['edit'])){
            //TODO escape input
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $address = $_POST['address'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $pid = $_SESSION['pid'];
            $conn = connect();

            $sql = "UPDATE persons P SET FIRST_NAME='$firstname', LAST_NAME='$lastname', ADDRESS='$address', EMAIL='$email', PHONE='$phone' WHERE P.person_id='$pid'";
            $stid = oci_parse($conn, $sql );
            $res=oci_execute($stid);

            if(!$res){
	        $err = oci_error($stid);
	        echo htmlentities($err['message']);
                oci_close($conn);
                header("Location: login.php");
                exit;
            }
            oci_commit($conn);
            oci_close($conn);
            header("Location: user-settings.php");
            exit;
        }
        ?>
	
    </body>
</html>
