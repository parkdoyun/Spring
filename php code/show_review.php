<?php

include 'mysql_connect.php';

// 사용자 ID 입력받고 모든 후기 정보와 매장 이름 반환

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    $post_id = $_GET['post_id'];

    $sql = "select * from S_Comms where ID = '".$post_id."'";
    $result = mysqli_query($con, $sql);


    // 결과 넣을 배열
    $return_array = array();

    while($row = mysqli_fetch_array($result))
    {
        $sql2 = "select S_Name from S_Loc where S_ID = '".$row[1]."'";
        $result2 = mysqli_query($con, $sql2);

        $row2 = mysqli_fetch_array($result2);
        
        array_push($return_array, ["_COMMS_ID"=>$row[0], "_S_ID"=>$row[1], "_COMM"=>$row[3], "_STAR"=>$row[4],"_S_NAME"=>$row2[0]]);
    }

    // return
    header("Content-Type:application/json");
    echo json_encode($return_array, JSON_PRETTY_PRINT);
}


mysqli_close($con); // db close

?>