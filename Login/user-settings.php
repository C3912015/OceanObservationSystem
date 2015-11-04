<html>
    <body>
	<h1>Welcome to Ocean Observation System</h1>
        <?php
        if(isset($_POST['settings'])){
            $row=$_POST['settingsName'];            		
            $USER_PASSWORD=$_POST['user_password'];   
        foreach ($row as $item){
	        echo $item.'&nbsp;';     	      
	}
        ?>
	
    </body>
</html>
