<?php

include 'mysql_connect.php';

// 매장 ID 입력받고 모든 후기 정보 반환

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    $post_s_id = $_GET['post_s_id'];
    // 결과 넣을 배열
    $return_array = array();

    //$sql = "select S_AvrStar from S_User where S_ID = '".$post_s_id."'";
    //$result2 = mysqli_query($con, $sql);
    //if($row2 = mysqli_fetch_array($result2))
    //{
      //  $return_array = array("_S_AVR_STAR"=>$row2[0]);
    //}

    $sql = "select * from S_Comms where S_ID = '".$post_s_id."'";
    $result = mysqli_query($con, $sql);

    while($row = mysqli_fetch_array($result))
    {
        array_push($return_array, ["_COMMS_ID"=>$row[0], "_ID"=>$row[2], "_COMM"=>$row[3], "_STAR"=>$row[4]]);
    }

    // return
    header("Content-Type:application/json");
    echo json_encode($return_array, JSON_PRETTY_PRINT);
 }
 
 
 mysqli_close($con); // db close
 
 ?>