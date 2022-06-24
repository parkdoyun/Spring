<?php

include 'mysql_connect.php';

// 로그인하는 php
if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    $post_id = $_GET['post_id'];
    $post_pw = $_GET['post_pw'];

    $return_string = "F:FAILED"; // 보낼 string

    // UserInfo 테이블 조회
    $sql = "SELECT * FROM UserInfo WHERE ID = '".$post_id."' AND PWD = '".$post_pw."'"; // query
    $result = mysqli_query($con, $sql);

    if($row2 = mysqli_fetch_array($result)) // 조회 성공했으면 (select는 이렇게 안 하면 다 성공으로 뜸)
    {
        $sql2 = "SELECT * FROM D_UserInfo WHERE ID = '".$post_id."'"; // query
        $result2 = mysqli_query($con, $sql2);       
       
        if($row = mysqli_fetch_array($result2)){ // 해당 row 있으면 관련 정보 다시 전송
            
            $return_string = "S:".$row[1]; // return_string "S:이름"으로 변경
        }
    }

    header("Content-Type:application/json");
    echo json_encode(array('_STRING'=>$return_string, '_BUSI_YN'=>$row2[2]), JSON_PRETTY_PRINT);
}


mysqli_close($con); // db close

?>