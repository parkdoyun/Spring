<?php

include 'mysql_connect.php';

// 예약 등록

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    header("Content-Type:application/json");
    
    $post_s_id = $_GET['post_s_id'];
    $post_tbl_id = $_GET['post_tbl_id'];
    $post_id = $_GET['post_id'];
    $post_time = $_GET['post_time'];
    
    $return_string = "F:FAILED"; // 보낼 string, default : 가입 실패

    // 먼저 제일 마지막 예약 숫자 확인
    $sql = "select R_ID from Rev order by R_ID DESC";
    $result = mysqli_query($con, $sql);
    $new_R_ID = 1;

    // 예약 있다면 제일 큰 수 + 1이 새로운 R_ID
    if($row = mysqli_fetch_array($result))
    {        
        $new_R_ID = $row[0] + 1;
    } 

    $return_string = "INSERT INTO Rev VALUES(".$new_R_ID.",'".$post_s_id."','".$post_id."','".$post_time."', 0, ".$post_tbl_id.")";
  
    // Rev insert
    $sql2 = "INSERT INTO Rev VALUES(".$new_R_ID.",'".$post_s_id."','".$post_id."','".$post_time."', 0, ".$post_tbl_id.")";
    $result2 = mysqli_query($con, $sql2);

    if($result2) // Rev insert 성공
    {     
        $return_string = "S:SUCCESS";        
    }
    

    header("Content-Type:application/json");
    echo json_encode(array('_STRING'=>$return_string), JSON_PRETTY_PRINT);


}
mysqli_close($con); // db close

?>