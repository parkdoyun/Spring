<?php

include 'mysql_connect.php';

// 예약 승인/거절 정보 입력받아 update

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{

    $post_r_cnt = $_GET['post_allow_refuse'];
    $post_r_id = $_GET['post_r_id'];
    $post_s_id = $_GET['post_s_id'];

    $return_string = "F:FAILED";

    // Rev 갱신
    $sql = "update Rev SET R_Cnt = ".$post_r_cnt." where S_ID = '".$post_s_id."' and R_ID = ".$post_r_id;
    $result = mysqli_query($con, $sql);

    if($result)
    {
        $return_string = "S:SUCCESS";
    }
    else{
        $return_string = "F:UPDATE IS FAILED";
    }


    header("Content-Type:application/json");
    echo json_encode(array('_STRING'=>$return_string), JSON_PRETTY_PRINT);


}
mysqli_close($con); // db close

?>