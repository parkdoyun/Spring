<?php

include 'mysql_connect.php';

// 사용자 후기 정보 수정

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    header("Content-Type:application/json");   
      
    $post_id = $_GET['post_id'];
    $post_comms_id = $_GET['post_comms_id'];
    $post_s_id = $_GET['post_s_id'];
    $post_comm = $_GET['post_comm'];
    $post_star = $_GET['post_star'];
    
    $return_string = "F:FAILED"; // 보낼 string, default : 가입 실패

    
    // S_Comms 정보 수정
    $sql = "update S_Comms SET Comm = '".$post_comm."', Star = ".$post_star." where ID = '".$post_id."' and Comms_ID = ".$post_comms_id." and S_ID = '".$post_s_id."'";
    $result = mysqli_query($con, $sql);

    if($result) // S_Comms 수정 성공
    {         
        $return_string = "S:SUCCESS";
    }
    

    header("Content-Type:application/json");
    echo json_encode(array('_STRING'=>$return_string), JSON_PRETTY_PRINT);


}
mysqli_close($con); // db close

?>