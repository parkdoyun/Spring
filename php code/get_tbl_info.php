<?php

include 'mysql_connect.php';

// 매장 ID, tbl ID 입력받고 관련 테이블 내용 다 반환

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    $post_s_id = $_GET['post_s_id'];
    $post_tbl_id = $_GET['post_tbl_id'];
    
    $sql = "select * from Tbl where S_ID = '".$post_s_id."' and Tbl_ID = ".$post_tbl_id;
    $result2 = mysqli_query($con, $sql);
    if($row2 = mysqli_fetch_array($result2))
    {
        // return
        header("Content-Type:application/json");
        echo json_encode(array("_USE_YN"=>$row2[2], "_USE_TIME"=>$row2[3], "_POS_X"=>$row2[4], "_POS_Y"=>$row2[5]), JSON_PRETTY_PRINT);
    }
    else{
        header("Content-Type:application/json");
        echo json_encode(array("_USE_YN"=>"F:ID CANNOT FOUND"), JSON_PRETTY_PRINT);
    } 
 }
 
 
 mysqli_close($con); // db close
 
 ?>