<html>
    <body>
	<h1>Welcome to Ocean Observation System</h1>

        <?php   
        //connect to database   	 
        include("/compsci/webdocs/jchang3/web_docs/PHPconnectionDB.php");
        $conn = connect();

        //validate user
        if(isset($_POST['validate'])){
            $USER=$_POST['user'];            		
            $USER_PASSWORD=$_POST['user_password'];        	      
	}

        //sql command
        $sql = "SELECT * FROM users U WHERE U.user_name='$USER' AND U.password='$USER_PASSWORD'";
           
        //Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql );
          
        //Execute a statement returned from oci_parse()
        $res=oci_execute($stid); 
        
        //return to homepage function
        function Redirect($url, $permanent = false){
            header('Location: ' . $url, true, $permanent ? 301 : 302);
            exit();
        }
           
        //if error, retrieve the error using the oci_error() function & output an error
        if(!$res){
	$err = oci_error($stid);

	//echo htmlentities($err['message']);
        oci_close($conn);
        Redirect('http://consort.cs.ualberta.ca/~jchang3/login.html', false);
        }
        
        $row = oci_fetch($stid);
        //invalid user
        if(!$row){
        oci_close($conn);
        Redirect('http://consort.cs.ualberta.ca/~jchang3/login.html', false);
        }
 
        echo 'Thank You !<br/> Your username is '.$USER.'.<br/> Your Name is '.$USER_PASSWORD.'.<br/>';

        //display personal information
        $pid = oci_result($stid, 'PERSON_ID');
        //echo $pid;
        $sql = "SELECT * FROM persons P WHERE P.person_id='$pid'";
        $stid = oci_parse($conn, $sql );
        $res=oci_execute($stid);

        if(!$res){
	$err = oci_error($stid);
	echo htmlentities($err['message']);
        }
        else{
            $row = oci_fetch_array($stid, OCI_ASSOC);
            foreach ($row as $item){
	        echo $item.'&nbsp;';
	    }

        $firstname = oci_result($stid, 'FIRST_NAME');
        $lastname = oci_result($stid, 'LAST_NAME');
        $address = oci_result($stid, 'ADDRESS');
        $email = oci_result($stid, 'EMAIL');
        $phone = oci_result($stid, 'PHONE');

            //edit personal information
            /*<form name="registration" method="post" action="PHPexample5.php">
            CCID : <input type="text" name="ccid"/> <br/>
            Name : <input type="text" name="fullname"/><br/>
            <input type="submit" name="validate" value="OK"/>
            </form>*/
            
        }

        //close connection
        oci_close($conn);
	?>

        <form id='settings' action='user-settings.php' method ="post">
            <input type="hidden" name="settings" value="<?php echo $pid; ?>"/>
            <a href="./user-settings.html" onclick="document.getElementById('settings').submit();">Change Personal Information</a>
        </form>
	
        <?php   
/*
        //first method    	 
		if(isset($_POST['validate'])){        	
			$USER=$_POST['user'];            		
			$USER_PASSWORD=$_POST['user_password'];
	           	echo 'Thank You !<br/> Your username is '.$USER.'.<br/> Your Name is '.$USER_PASSWORD.'.';         
		}	
*/
	?>
        


	<br/>
	
	<?php     
		// second method	 
	/*	if(isset($_POST['validate'])){        	
		       echo 'Thank You !'.'<br/>'; 
	                    foreach($_POST as $Key => $Value){
		                echo 'Your '.$Key.' is '.$Value.'<br/>';           
		         } 		
		}	
	 */?>
    </body>
</html>
