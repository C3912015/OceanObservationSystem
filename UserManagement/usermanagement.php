<!-- Sensor and User Management Options
Sys Admin can:
- Enter user
- Update user
- Remove user
- Add sensor
- Remove sensor
Add/Update/Remove User
-->


<html>
  <body>
    <?php
       //connection function
       include("../../PHPconnectionDB.php");

       // if user is admin
       //establish connection
       $conn = connect();

 	// Delete User
       if($_POST["userRemove"]){
          $user_ID = trim($_POST['userNameR']);
          $count = 0;
	  echo $user_ID;

          //check if user exists in DB
          // if it does, remove it
          if($user_ID != NULL){
             $sqlUserExist = "select * from users 
				where user_name = '{$user_ID}'";
             $userExist = oci_parse($conn, $sqlUserExist);
             $uExistRes = oci_execute($userExist);

             while (($row = oci_fetch_array($userExist, OCI_ASSOC)))
             {
                $count++;
             }
             //user exists, so remove it
             if($count > 0){
		// remove from users	
                $sqlDEL = "DELETE FROM users 
			WHERE user_name='{$user_ID}'";
                $rmUser = oci_parse($conn, $sqlDEL);
                $res = oci_execute($rmUser);
                echo 'User removed';
             //user doesn't exist
             }else{echo "Unable to remove nonexistant user.";}
          //no input given
          //oci_free_statement($UserExist);
          } else { echo "No user given"; }
       }
	
       //Add user
       if($_POST["userAdd"]){
          //get values and check they are not null ??
         if($_POST['userNameA'] != NULL){ $user_ID = $_POST['userNameA']; 
         } else { 
            echo 'No username given'; 
            echo '<p><a href="http://consort.cs.ualberta.ca/~olexson/OceanObservationSystem/UserManagement/usermanagementIn.php">Go Back</a>';
            exit;
        }
        $password = $_POST['userPWA'];
        $role = $_POST['userRoleA'];
        $person_id = $_POST['userPersonA'];
	$date = $_POST['userDateA'];
	
	//TODO check that person exists 

         $sqlADD = "INSERT INTO users VALUES ('{$user_ID}',
         '{$password}', '{$role}', {$person_id}, TO_DATE('$date','YYYY-MM-DD'))";
         echo $sqlADD;
         $addUser = oci_parse($conn, $sqlADD);
         $res = oci_execute($addUser);

       } 

	// Update user
	//Does not handle username change right now - add new username textbox

	if($_POST["userUpdate"]){
          //get values and check they are not null ??
         if($_POST['userNameU'] != NULL){ $user_ID = $_POST['userNameU']; 
         } else { 
            echo 'No username given'; 
            echo '<p><a href="http://consort.cs.ualberta.ca/~olexson/OceanObservationSystem/UserManagement/usermanagementIn.php">Go Back</a>';
            exit;
        }

	//get user with requested username
	$sqlGetUser = "SELECT * from users 
			WHERE user_name = '{$user_ID}'";
	$getUser = oci_parse($conn, $sqlGetUser);
	$res = oci_execute($getUser);

	//go through each field to see if it was updated,
	// otherwise set it to current value
	$row = oci_fetch_row($getUser);
	echo $row[0];
	echo $row[1];
	echo $row[2];
	if($_POST['userPWU']!=NULL){
		$password = $_POST['userPWU'];
	echo $password;
	} else { $password = $row[1]; }

	if($_POST['userRoleU'] != NULL){
		$role = $_POST['userRoleU'];
	} else { $role = $row[2]; }

	if($_POST['userPersonU'] != NULL){
		$person_id = $_POST['userPersonU'];
	} else { $person_id = $row[3]; }

	if($_POST['userDateU'] != NULL){
		$date = $_POST['userDateU'];
	} else { $date = $row[4]; }

	//update user
	echo $password;
         $sqlUpdate = "UPDATE users SET user_name = '{$user_ID}',
         password = '{$password}', role = '{$role}', 
	person_id = {$person_id}, 
	date_registered = TO_DATE('$date','YYYY-MON-DD') 
	WHERE user_name = '{$user_ID}'";
         echo $sqlUpdate;
         $updateUser = oci_parse($conn, $sqlUpdate);
         $res = oci_execute($updateUser);

       }

	
	//add person
		

	//remove person
 	if($_POST["personRemove"]){
          $user_ID = trim($_POST['personR']);
          $count = 0;

          //check if person exists in DB
          // if it does, remove it
          if($user_ID != NULL){
             $sqlPersonExist = "select * from persons 
				where person_id = '{$user_ID}'";
             $personExist = oci_parse($conn, $sqlPersonExist);
             $pExistRes = oci_execute($personExist);

             while (($row = oci_fetch_array($personExist, OCI_ASSOC)))
             {
                $count++;
             }
             //user exists, so remove it
             if($count > 0){

		//remove from subs
                $sqlDELsub = "DELETE FROM subscriptions 
			WHERE person_id='{$user_ID}'";
                $rmPersonsub = oci_parse($conn, $sqlDELsub);
                $res = oci_execute($rmPersonsub);

		//remove frm users
                $sqlDELu = "DELETE FROM users 
			WHERE person_id='{$user_ID}'";
                $rmPersonU = oci_parse($conn, $sqlDELu);
                $res = oci_execute($rmPersonU);

		// remove from person
                $sqlDEL = "DELETE FROM persons 
			WHERE person_id='{$user_ID}'";
                $rmPerson = oci_parse($conn, $sqlDEL);
                $res = oci_execute($rmPerson);
                echo 'Person removed';
             //user doesn't exist
             }else{echo "Unable to remove nonexistant person.";}
          //no input given
          //oci_free_statement($UserExist);
          } else { echo "No person ID given"; }
       }

	//update person - cannot update person_id (add box like user update)
	

       echo
       '<p><a href="http://consort.cs.ualberta.ca/~olexson/OceanObservationSystem/UserManagement/usermanagementIn.php">Go
       Back</a>';
       oci_close($conn);
       
    ?>
  </body>
</html>
