<?php

include 'mysql_connect.php';

// 매장 ID 입력받고 사업자 ID 반환

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    $post_s_id = $_GET['post_s_id'];
    // 결과 넣을 배열

    $sql = "select ID from S_Skeeper where S_ID = '".$post_s_id."'";
    $result2 = mysqli_query($con, $sql);
    if($row2 = mysqli_fetch_array($result2))
    {
        // return
        header("Content-Type:application/json");
        echo json_encode(array("_ID"=>$row2[0]), JSON_PRETTY_PRINT);
    }
    else{
        header("Content-Type:application/json");
        echo json_encode(array("_ID"=>"F:STORE CANNOT FOUND"), JSON_PRETTY_PRINT);
    } 
 }
 
 
 mysqli_close($con); // db close
 
 ?>