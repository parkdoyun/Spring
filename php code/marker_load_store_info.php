<?php

include 'mysql_connect.php';

// 좌표로 매장 정보 불러오기
// 식당 관련 매장만 불러옴 (나머지는 X)

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    header("Content-Type:application/json");

    $post_pos = $_GET['post_pos'];

    $return_S_ID = "F:FAILED"; // 실패할 경우, fail return
    $return_Addr = "";
    $return_S_Name = "";
    $return_S_Type="0";
    $return_O_Time="";
    $return_C_Time="";

    // S_Loc이랑 Store 테이블 조회해서 둘다 조회되면 가져옴

    // S_Loc 조회
    $sql = "SELECT * FROM S_Loc WHERE S_Pos = '".$post_pos."'"; // query
    $result = mysqli_query($con, $sql);

    if($row = mysqli_fetch_array($result)) // 조회 성공했으면 (select는 이렇게 안 하면 다 성공으로 뜸)
    {
        $return_S_ID = $row[0];
        
        // Store 조회 (앞자리 숫자 -> 가게 관련 식당만 조회 [지하철, 학교, 기타 상가 조회 X])
        $sql2 = "SELECT * FROM Store WHERE S_ID = '".$return_S_ID."' and LEFT(S_ID, 1) >= '1' and LEFT(S_ID, 1) <= '9'";
        $result2 = mysqli_query($con, $sql2);

        if($row2 = mysqli_fetch_array($result2)) // Store 조회 성공 -> data 전송
        {
            // $row : S_Loc, $row2 : Store
            $return_Addr = $row[3];
            $return_S_Name = $row[4];
            $return_S_Type = $row2[1];
            $return_O_Time = $row2[2];
            $return_C_Time = $row2[3];
            echo json_encode(array('_S_ID'=>$return_S_ID, '_ADDR'=>$return_Addr, '_S_NAME'=>$return_S_Name,
        '_S_TYPE'=>$return_S_Type, '_O_TIME'=>$return_O_Time, '_C_TIME'=>$return_C_Time), JSON_PRETTY_PRINT);

        }
        else // Store 조회 실패
        {
            echo json_encode(array('_S_ID'=>"F:FAILED", '_ADDR'=>$return_Addr, '_S_NAME'=>$return_S_Name,
        '_S_TYPE'=>$return_S_Type, '_O_TIME'=>$return_O_Time, '_C_TIME'=>$return_C_Time), JSON_PRETTY_PRINT);

        }

    }
    else // S_Loc 조회 실패
    {
        echo json_encode(array('_S_ID'=>"F:FAILED", '_ADDR'=>$return_Addr, '_S_NAME'=>$return_S_Name,
    '_S_TYPE'=>$return_S_Type, '_O_TIME'=>$return_O_Time, '_C_TIME'=>$return_C_Time), JSON_PRETTY_PRINT);

    }


}


mysqli_close($con); // db close

?>