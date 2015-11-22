<?php         
//session start
session_name('Login');
session_start();
?>

<?php
if(!$_SESSION['id']):
        header("Location: login.php");
        exit;
?>

<?php else:?>
<?php 
    include("./PHPconnectionDB.php");
    $conn = connect();
    $id = $_GET['id'];
    $sql = "SELECT thumbnail FROM images WHERE image_id=:id";
    $stid = oci_parse($conn, $sql );
    oci_bind_by_name($stid, ':id', $id);
    $res=oci_execute($stid);
    
    if($res){
        $row = oci_fetch($stid);
        if($row = oci_fetch($stid)){
            $thumb = $row['THUMBNAIL'];
            header('Content-Type: image/jpeg');
            echo $thumb;
        }
    }
    else
        exit;
    oci_free_statement($stid);
    oci_close($conn);
?>

<?php endif;?>

