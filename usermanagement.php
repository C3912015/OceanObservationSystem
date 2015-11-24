<!-- Sensor and User Management Options
Sys Admin can:
- Enter user/person
- Update user/person
- Remove user/person
- Add sensor
- Remove sensor
Add/Update/Remove User/Person
-->
<!-- start session -->
<?php 
session_name('Login');
session_start();?>
<?php if($_SESSION['role']!='a'){
	header("Location:login.php");
	exit;
} ?>
<html>
  <body>
    <?php
       //connection function
       include("PHPconnectionDB.php");

       // if user is admin

       //establish connection
       $conn = connect();

 	// Delete User
       if($_POST["userRemove"]){
          $user_ID = trim($_POST['userNameR']);
          $count = 0;

          //check if user exists in DB
          // if it does, remove it
          if($user_ID != NULL){
             $sqlUserExist = "select * from users 
				where user_name = :id";
             $userExist = oci_parse($conn, $sqlUserExist);
	     oci_bind_by_name($userExist,":id",$user_ID);
             $uExistRes = oci_execute($userExist);

             while (($row = oci_fetch_array($userExist, OCI_ASSOC)))
             {
                $count++;
             }
             //user exists, so remove it
             if($count > 0){
		// remove from users	
                $sqlDEL = "DELETE FROM users 
			WHERE user_name=:id";
                $rmUser = oci_parse($conn, $sqlDEL);
	        oci_bind_by_name($rmUser,":id",$user_ID);
                $res = oci_execute($rmUser);
                oci_commit($conn);
                echo 'User removed';
             //user doesn't exist
             }else{echo "Unable to remove nonexistant user.";}
          //no input given
          } else { echo "No user given"; }
       }
	
       //Add user
       if($_POST["userAdd"]){
          //get values and check if username is given
         if($_POST['userNameA'] != NULL){ $user_ID = $_POST['userNameA']; 
         } else { 
            echo 'No username given'; 
            echo '<p><a href="usermanagementIn.php">Go Back</a>';
            exit;
        }
        $password = $_POST['userPWA'];
        $role = $_POST['userRoleA'];
        $person_id = $_POST['userPersonA'];
	$date = $_POST['userDateA'];
	
         $sqlADD = "INSERT INTO users VALUES (:userid,
         :upassw, :urole, :personid, TO_DATE(:rdate,'YYYY-MM-DD'))";
         $addUser = oci_parse($conn, $sqlADD);

	 oci_bind_by_name($addUser,":userid",$user_ID);
	 oci_bind_by_name($addUser,":upassw",$password);
	 oci_bind_by_name($addUser,":urole",$role);
	 oci_bind_by_name($addUser,":personid",$person_id);
	 oci_bind_by_name($addUser,":rdate",$date);

         $res = oci_execute($addUser);
         oci_commit($conn);

       } 

	// Update user
	// no doesn't change person_id in other tables (subs and persons)
	if($_POST["userUpdate"]){
          //get values, check that username isn't null
         if($_POST['userNameU'] != NULL){ $user_ID = $_POST['userNameU']; 
         } else { 
            echo 'No username given'; 
            echo '<p><a href="usermanagementIn.php">Go Back</a>';
            exit;
        }

	//get user with requested username
	$sqlGetUser = "SELECT * from users 
			WHERE user_name = :id";
	$getUser = oci_parse($conn, $sqlGetUser);
	oci_bind_by_name($getUser,":id",$user_ID);
	$res = oci_execute($getUser);

	//go through each field to see if it was updated,
	// otherwise set it to value from query
	$row = oci_fetch_row($getUser);

	if($_POST['userNewU']!=NULL){
		$id = $_POST['userNewU'];
	} else { $id = $row[0]; }

	if($_POST['userPWU']!=NULL){
		$password = $_POST['userPWU'];
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

	//query to update user
         $sqlUpdate = "UPDATE users SET user_name = :id,
         password = :passw, role = :role, 
	person_id = :perid, 
	date_registered = TO_DATE(:rdate,'YYYY-MON-DD') 
	WHERE user_name = :oldid";
         $updateUser = oci_parse($conn, $sqlUpdate);

	 oci_bind_by_name($updateUser,":id",$id);
	 oci_bind_by_name($updateUser,":passw",$password);
	 oci_bind_by_name($updateUser,":role",$role);
	 oci_bind_by_name($updateUser,":perid",$person_id);
	 oci_bind_by_name($updateUser,":rdate",$date);
	 oci_bind_by_name($updateUser,":oldid",$user_ID);

         $res = oci_execute($updateUser);
	 oci_commit($conn);

       }

	
	//add person
	 if($_POST["addPerson"]){
          //get values and check that person_id is not null
         	if($_POST['personIDA'] != NULL){ $user_ID = $_POST['personIDA']; 
         } else { 
            echo 'No person ID given'; 
            echo '<p><a href="usermanagementIn.php">Go Back</a>';
            exit;
        }

        $firstName = $_POST['personFirstA'];
        $lastName = $_POST['personLastA'];
        $address = $_POST['personAddressA'];
	$email = $_POST['personEmailA'];
	$phone = $_POST['personPhoneA'];


         $sqlADD = "INSERT INTO persons VALUES (:id,
         :first, :last, :address, :email, :phone)";
         $addPerson = oci_parse($conn, $sqlADD);

	 oci_bind_by_name($addPerson,":id",$user_ID);
	 oci_bind_by_name($addPerson,":first",$firstName);
	 oci_bind_by_name($addPerson,":last",$lastName);
	 oci_bind_by_name($addPerson,":address",$address);
	 oci_bind_by_name($addPerson,":email",$email);
	 oci_bind_by_name($addPerson,":phone",$phone);

         $res = oci_execute($addPerson);
         oci_commit($conn);
	echo "Person added";

       } 

		

	//remove person
 	if($_POST["personRemove"]){
          $user_ID = trim($_POST['personR']);
          $count = 0;

          //check if person exists in DB
          // if it does, remove it
          if($user_ID != NULL){
             $sqlPersonExist = "select * from persons 
				where person_id = :id";
             $personExist = oci_parse($conn, $sqlPersonExist);
	
	     oci_bind_by_name($personExist,":id",$user_ID);

             $pExistRes = oci_execute($personExist);

             while (($row = oci_fetch_array($personExist, OCI_ASSOC)))
             {
                $count++;
             }
             //user exists, so remove it
             if($count > 0){

		//remove from subs
                $sqlDELsub = "DELETE FROM subscriptions 
			WHERE person_id=:id";
                $rmPersonsub = oci_parse($conn, $sqlDELsub);
	        oci_bind_by_name($rmPersonsub,":id",$user_ID);
                $res = oci_execute($rmPersonsub, OCI_DEFAULT);

		//remove from users
                $sqlDELu = "DELETE FROM users 
			WHERE person_id=:id";
                $rmPersonU = oci_parse($conn, $sqlDELu);
	        oci_bind_by_name($rmPersonU,":id",$user_ID);
                $res = oci_execute($rmPersonU, OCI_DEFAULT);

		// remove from persons
                $sqlDEL = "DELETE FROM persons 
			WHERE person_id=:id";
                $rmPerson = oci_parse($conn, $sqlDEL);
	        oci_bind_by_name($rmPerson,":id",$user_ID);
                $res = oci_execute($rmPerson, OCI_DEFAULT);
		oci_commit($conn);
                echo 'Person removed';
             //user doesn't exist
             }else{echo "Unable to remove nonexistant person.";}
          //no input given
          } else { echo "No person ID given"; }
       }

	//update person 
	//doesn't change values in other tables (users and subs)
		if($_POST["updatePerson"]){
          //get values, check that person id isn't null
         if($_POST['personIDU'] != NULL){ $user_ID = $_POST['personIDU']; 
         } else { 
            echo 'No person ID given'; 
            echo '<p><a href="usermanagementIn.php">Go Back</a>';
            exit;
        }

	//get person with requested id
	$sqlGetPerson = "SELECT * from persons 
			WHERE person_id = :id";
	$getPerson = oci_parse($conn, $sqlGetPerson);
	oci_bind_by_name($getPerson,":id",$user_ID);
	$res = oci_execute($getPerson);

	//go through each field to see if it was updated,
	// otherwise set it to value from query
	$row = oci_fetch_row($getPerson);

	if($_POST['personFirstU']!=NULL){
		$first = $_POST['personFirstU'];
	} else { $first = $row[1]; }

	if($_POST['personLastU'] != NULL){
		$last = $_POST['personLastU'];
	} else { $last = $row[2]; }

	if($_POST['personAddressU'] != NULL){
		$address = $_POST['personAddressU'];
	} else { $address = $row[3]; }

	if($_POST['personEmailU'] != NULL){
		$email = $_POST['personEmailU'];
	} else { $email = $row[4]; }

	if($_POST['personPhoneU'] != NULL){
		$phone = $_POST['personPhoneU'];
	} else { $phone = $row[5]; }

	//update in persons
         $sqlUpdate = "UPDATE persons SET person_id = :id,
         first_name = :first, last_name = :last, 
	address = :address, email = :email, 
	phone = :phone	 
	WHERE person_id = :id";
         $updateUser = oci_parse($conn, $sqlUpdate);

	 oci_bind_by_name($updateUser,":id",$user_ID);
	 oci_bind_by_name($updateUser,":first",$first);
	 oci_bind_by_name($updateUser,":last",$last);
	 oci_bind_by_name($updateUser,":address",$address);
	 oci_bind_by_name($updateUser,":email",$email);
	 oci_bind_by_name($updateUser,":phone",$phone);

         $res = oci_execute($updateUser);
         oci_commit($conn);
       }

       echo
       '<p><a href="usermanagementIn.php">Go
       Back</a>';
       oci_close($conn);
    ?>
<p><a href="login.php">Back to Main Page</a>

  </body>
</html>
