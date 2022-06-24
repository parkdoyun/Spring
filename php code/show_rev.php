<?php

include 'mysql_connect.php';

// 사용자 ID 입력받고 모든 예약 정보 반환 및 매장 이름 반환

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    $post_id = $_GET['post_id'];

    $sql = "select * from Rev where ID = '".$post_id."'";
    $result = mysqli_query($con, $sql);
    
    // 결과 넣을 배열
    $return_array = array();

    while($row = mysqli_fetch_array($result))
    {
        $sql2 = "select S_Name from S_Loc where S_ID = '".$row[1]."'";
        $result2 = mysqli_query($con, $sql2);

        $row2 = mysqli_fetch_array($result2);
        array_push($return_array, ["_R_ID"=>$row[0], "_S_ID"=>$row[1], "_S_TIME"=>$row[3], "_R_CNT"=>$row[4],  "_TBL_ID"=>$row[5], "_S_NAME"=>$row2[0]]);
    }

    // return
    header("Content-Type:application/json");
    echo json_encode($return_array, JSON_PRETTY_PRINT);
}


mysqli_close($con); // db close

?>