<?php

include 'mysql_connect.php';

// 매장 ID, 테이블 ID 입력받고 해당 테이블 예약 정보 반환

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    $post_s_id = $_GET['post_s_id'];
    $post_tbl_id = $_GET['post_tbl_id'];

    $sql = "select * from Rev where S_ID = '".$post_s_id."' and Tbl_ID = ".$post_tbl_id;
    $result = mysqli_query($con, $sql);

    // 결과 넣을 배열
    $return_array = array();

    while($row = mysqli_fetch_array($result))
    {
        array_push($return_array, ["_R_ID"=>$row[0], "_ID"=>$row[2], "_S_TIME"=>$row[3], "_R_CNT"=>$row[4]]);
    }

    // return
    header("Content-Type:application/json");
    echo json_encode($return_array, JSON_PRETTY_PRINT);
 }
 
 
 mysqli_close($con); // db close
 
 ?>