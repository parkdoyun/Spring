<?php

include 'mysql_connect.php';

// 예약 상태 변경 혹은 삭제

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    header("Content-Type:application/json");   
      
    $post_s_id = $_GET['post_s_id'];
    $post_tbl_id = $_GET['post_tbl_id'];
    $post_id = $_GET['post_id'];
    $post_time = $_GET['post_time'];
    $post_check = $_GET['post_check'];

    
    $return_string = "F:FAILED"; // 보낼 string, default : 수정 실패

    if($post_check == "1") // 수락
    {
        $sql = "update Rev SET R_Cnt = 1 where ID = '".$post_id."' and S_ID = '".$post_s_id."' and Tbl_ID = ".$post_tbl_id." and S_Time = '".$post_time."'";
        $result = mysqli_query($con, $sql);
    
        if($result) // Rev 수정 성공
        {         
            $return_string = "S:SUCCESS";
        }
    }
    else if($post_check == "-1")
    {
        // 삭제
        $sql = "delete from Rev where ID = '".$post_id."'and S_ID = '".$post_s_id."' and Tbl_ID = ".$post_tbl_id." and S_Time = '".$post_time."'";
        $result = mysqli_query($con, $sql);
    
        if($result) // S_Comms 수정 성공
        {         
            $return_string = "S:SUCCESS";
        }

    }
    
    

    header("Content-Type:application/json");
    echo json_encode(array('_STRING'=>$return_string), JSON_PRETTY_PRINT);


}
mysqli_close($con); // db close

?>