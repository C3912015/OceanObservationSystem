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
    $type = $_GET['type'];
    //image download
    if($type == "i"){
        $sql = "SELECT * FROM images WHERE image_id=:id";
        $stid = oci_parse($conn, $sql );
        oci_bind_by_name($stid, ':id', $id);
        $res=oci_execute($stid);
        
        if($res){
            if($row = oci_fetch_assoc($stid)){
                $filedata = $row['RECOREDED_DATA']->read($row['RECOREDED_DATA']->size());
                $filename = $id;
                header('Content-Type: image/jpeg');
                header('Content-Disposition: attachment; filename="'.$filename.'.jpeg"');
		echo $filedata;
            }
        }
        else
            exit;
    }
    //audio download
    else{
        if($type == "a"){
            $sql = "SELECT * FROM audio_recordings WHERE recording_id=:id";
            $stid = oci_parse($conn, $sql );
            oci_bind_by_name($stid, ':id', $id);
            $res=oci_execute($stid);
            
            if($res){
                if($row = oci_fetch_assoc($stid)){
                    $filedata = $row['RECORDED_DATA']->read($row['RECORDED_DATA']->size());
                    $filename = $id;
                    header('Content-Type: audio/wav');
                    header('Content-Disposition: attachment; filename="'.$filename.'.wav"');
                    echo $filedata;
                }
            }
            else
                exit;
        }
        //scalar data download
        else{
            if($type == "s"){
                $sql = "SELECT * FROM scalar_data WHERE id=:id";
                $stid = oci_parse($conn, $sql );
                oci_bind_by_name($stid, ':id', $id);
                $res=oci_execute($stid);
                
                if($res){
                    if($row = oci_fetch_assoc($stid)){
                        $sensor_id = strval($row['SENSOR_ID']);
                        //$date_created = $row['DATE_CREATED'];
                        $date_created = date('d/m/Y H', strtotime($row['DATE_CREATED']));
                        $value = strval($row['VALUE']);
                        $filename = $id;
                        header('Content-Type: text/csv');
                        header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
                        echo $sensor_id.",".$date_created.",".$value;
                    }
                }
                else
                    exit;
            }
        }

    }


    //oci_free_statement($stid);
    oci_close($conn);
?>

<?php //closing the IF-ELSE construct 
endif;?>

