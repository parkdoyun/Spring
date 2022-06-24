<?php

include 'mysql_connect.php';

// 매장 ID 입력 받고 해당 매장 정보 반환

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    $post_s_id = $_GET['post_s_id'];

    $sql = "select * from S_Loc where S_ID = '".$post_s_id."'";
    $result = mysqli_query($con, $sql);

    $S_POS = "F:FAILED";
    $S_NAME = "";
    $S_TYPE = "";
    $S_ADDR = "";

    if($row = mysqli_fetch_array($result)) // 조회 성공
    {
        $S_ADDR = $row[3];
        $S_NAME = $row[4];
        $S_POS = $row[1];
        
        // type 찾기
        $sql = "select * from Store where S_ID = '".$post_s_id."'";
        $result2 = mysqli_query($con, $sql);
        if($row2 = mysqli_fetch_array($result2))
        {
            $S_TYPE = $row2[1];
        }
        else $S_POS = "F:FAILED";
    }

    // return
    header("Content-Type:application/json");
    echo json_encode(array('_S_POS'=>$S_POS, '_NAME'=>$S_NAME, '_TYPE'=>$S_TYPE, '_ADDR'=>$S_ADDR), JSON_PRETTY_PRINT);
}


mysqli_close($con); // db close

?>