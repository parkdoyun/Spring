<?php

include 'mysql_connect.php';

// 상호명 입력 받고 비슷한 매장 10개 반환

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    $post_name = $_GET['post_name'];

    $sql = "select * from S_Loc where LEFT(S_ID,1) >= 0 and LEFT(S_ID, 1) <= 9 and S_Name like '%".$post_name."%'";
    $result = mysqli_query($con, $sql);

    // 결과 넣을 배열
    $return_array = array();

    $i = 0;
    while($row = mysqli_fetch_array($result))
    {
        if($i == 10) break;
        $i = $i + 1;
        array_push($return_array, ["_NAME"=>$row[4], "_S_ID"=>$row[0], "_ADDR"=>$row[3]]);
    }

    // return
    header("Content-Type:application/json");
    echo json_encode($return_array, JSON_PRETTY_PRINT);
}


mysqli_close($con); // db close

?>