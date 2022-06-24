<?php

include 'mysql_connect.php';

// 매장 ID 입력받고 모든 테이블 정보 반환

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    $post_s_id = $_GET['post_s_id'];
    $sql = "select * from Tbl where S_ID = '".$post_s_id."'";
    $result = mysqli_query($con, $sql);

    // 결과 넣을 배열
    $return_array = array();

    while($row = mysqli_fetch_assoc($result))
    {
        array_push($return_array, ["_TBL_ID"=>$row['Tbl_ID'], "_USE_YN"=>$row['Use_Yn'], "_POS_X"=>$row['Pos_X'], "_POS_Y"=>$row['Pos_Y']]);
    }

    // return
    header("Content-Type:application/json");
    echo json_encode($return_array, JSON_PRETTY_PRINT);
}


mysqli_close($con); // db close

?>