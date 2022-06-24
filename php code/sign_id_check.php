<?php

include 'mysql_connect.php';

// id 중복확인 php
if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    $post_id = $_GET['post_id']; // 받아온 id
    $return_string = "S:NO DUPLICATE"; // 보낼 string, 중복 없을 때

    $sql = "SELECT * FROM UserInfo WHERE ID = '".$post_id."'"; // query

    $result = mysqli_query($con, $sql);

    $res = array();

    header("Content-Type:application/json");
    if($row = mysqli_fetch_array($result)){ // 값 존재하면 (중복 아이디 있으면)
        
        $return_string = "F:DUPLICATE"; // failed로 변경
    }

    echo json_encode(array('_STRING'=>$return_string), JSON_PRETTY_PRINT);
}


mysqli_close($con); // db close

?>