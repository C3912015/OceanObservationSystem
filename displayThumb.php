<?php         
//session start
session_name('Login');
session_start();
?>
<?php //If not logged in
if(!$_SESSION['id']):
        header("Location: login.php");
        exit;
?>

<?php 
//currently logged in
else:?>
<?php 
    include("./PHPconnectionDB.php");
    $conn = connect();
    $id = $_GET['id'];

    $sql = "SELECT * FROM images WHERE image_id=:id";
    $stid = oci_parse($conn, $sql );
    oci_bind_by_name($stid, ':id', $id);
    $res=oci_execute($stid);
    
    if($res){
        if($row = oci_fetch_assoc($stid)){
            $filedata = $row['THUMBNAIL']->read($row['THUMBNAIL']->size());
            $filename = $id;
            header('Content-Type: image/jpeg');
	        echo $filedata;
        }
    }
    else
        exit;

    //oci_free_statement($stid);
    oci_close($conn);
?>

<?php //closing the IF-ELSE construct 
endif;?>

