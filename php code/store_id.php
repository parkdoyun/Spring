<?php

include 'mysql_connect.php';

// 사용자 ID 입력 받고 소유 매장 ID 반환 (배열)

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    $post_id = $_GET['post_id'];
    // 결과 넣을 배열
    $return_array = array();


    $sql = "select * from S_Skeeper where ID = '".$post_id."'";
    $result = mysqli_query($con, $sql);

    while($row = mysqli_fetch_array($result))
    {
        array_push($return_array, ["_S_ID"=>$row[0]]);
    }

    // return
    header("Content-Type:application/json");
    echo json_encode($return_array, JSON_PRETTY_PRINT);
 }
 
 
 mysqli_close($con); // db close
 
 ?>