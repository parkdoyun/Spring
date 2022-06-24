<?php

include 'mysql_connect.php';

// 예약 정보 입력받고 해당 시간 (+-매장 평균 이용 시간) 전후로 예약 있는지 확인 

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    $post_s_id = $_GET['post_s_id'];
    $post_tbl_id = $_GET['post_tbl_id'];
    $post_id = $_GET['post_id'];
    $post_time = $_GET['post_time'];

    // table 평균 시간 구하기
    $sql = "select Tbl_AvrTime from S_User where S_ID = '".$post_s_id."'";
    $result = mysqli_query($con, $sql);
    if($row = mysqli_fetch_array($result))
    {
        $avr_time = $row[0];

        $sql = "select * from Rev where S_ID = '".$post_s_id."' and ID = '".$post_id."' and Tbl_ID = ".$post_tbl_id." and S_Time <= DATE_ADD('".$post_time."', INTERVAL ".$avr_time." MINUTE)
        and S_Time >= DATE_ADD('".$post_time."', INTERVAL -".$avr_time." MINUTE);";
        $result2 = mysqli_query($con, $sql);
        if($row2 = mysqli_fetch_array($result2))
        {
            // return
            header("Content-Type:application/json");
            echo json_encode(array("_STRING"=>"DUPLICATE"), JSON_PRETTY_PRINT);
        }
        else{
            header("Content-Type:application/json");
            echo json_encode(array("_STRING"=>"NO DUPLICATE"), JSON_PRETTY_PRINT);
        } 
    }
    else{
        header("Content-Type:application/json");
        echo json_encode(array("_STRING"=>"F:NOT EXIST STORE ID"), JSON_PRETTY_PRINT);
    }
 }
 
 
 mysqli_close($con); // db close
 
 ?>