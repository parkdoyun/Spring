<?php

include 'mysql_connect.php';

// 좌표 받고 가장 가까운 매장 정보 20개 배열로 반환
// 식당 관련 위치만 받아옴
// 매장 ID, 위치, 매장 유형 20개의 배열로 반환

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    $post_pos = $_GET['post_pos'];

    // x, y 따로 만들기
    $split_post_pos = explode(':', $post_pos);

    $pos_arr = []; // 반환할 배열
      
    // 가장 가까운 좌표 받아오기 (S_Pos_Search)
    $sql = "select distinct S_Pos_X, S_Pos_Y from S_Pos_Search where LEFT(S_ID, 1) >= '0' and LEFT(S_ID, 1) <= '9' order by (ABS(".$split_post_pos[0]."-S_Pos_X) + ABS(S_Pos_Y-".$split_post_pos[1]."))";

    $result = mysqli_query($con, $sql);

    $i = 1;
    while($row = mysqli_fetch_array($result))
    {
        if($i == 1)
        {
            $i = 2;
            continue;
        }
        if($i == 22) break;

        // S_Pos로 S_ID 찾기
        $tmp_S_Pos = $row[0].":".$row[1];
        $sql = "select * from S_Loc where S_Pos = '".$tmp_S_Pos."'";
        $result2 = mysqli_query($con, $sql);      
        if($row2 = mysqli_fetch_array($result2))
        {
            ;
        }
        else
        {
            continue;
        }

        // id로 매장 유형 찾기
        $tmp_id = $row2[0];
        $sql = "select * from Store where S_ID = '".$tmp_id."'";
        $result3 = mysqli_query($con, $sql);
        $row3 = mysqli_fetch_array($result3);

        // 매장 id, 위치, 유형 배열에 추가
        array_push($pos_arr, ["_S_ID"=>$tmp_id, "_POS"=>$tmp_S_Pos, "_S_TYPE"=>$row3[1]]);

        $i = $i + 1;
    }
    


    header("Content-Type:application/json");
    echo json_encode($pos_arr, JSON_PRETTY_PRINT);
}


mysqli_close($con); // db close

?>