<?php

include 'mysql_connect.php';

// 후기 등록 및 전체 별점 갱신

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    header("Content-Type:application/json");
    
    $post_s_id = $_GET['post_s_id'];    
    $post_id = $_GET['post_id'];
    $post_comm = $_GET['post_comm'];
    $post_star = $_GET['post_star'];
    
    $return_string = "F:FAILED"; // 보낼 string, default : 가입 실패

    // 먼저 제일 마지막 후기 숫자 확인
    $sql = "select Comms_ID from S_Comms where S_ID = '".$post_s_id."' order by Comms_ID DESC";
    $result = mysqli_query($con, $sql);
    $new_Comms_ID = 1;

    // 후가 있다면 제일 큰 수 + 1이 새로운 후기 ID
    if($row = mysqli_fetch_array($result))
    {
        $new_Comms_ID = $row[0] + 1;
    } 

  
    // 후기 등록
    $sql2 = "INSERT INTO S_Comms VALUES(".$new_Comms_ID.",'".$post_s_id."','".$post_id."','".$post_comm."',".$post_star.")";
    $result2 = mysqli_query($con, $sql2);

    if($result2) // 후기 성공 -> 별점 갱신
    {         
        $sql3 = "select * from S_User where S_ID = '".$post_s_id."'";
        $result3 = mysqli_query($con, $sql3);   
        if($row2 = mysqli_fetch_array($result3))
        {
            // 새로운 별점
            $tbl_cnt = $row2[3];
            $tbl_cnt = $tbl_cnt + 1;
            $new_star = ($row2[1] * $tbl_cnt + $post_star) / ($tbl_cnt + 1);
            // 갱신
            $sql_u = "update S_User SET S_AvrStar = ".$new_star." where S_ID = '".$post_s_id."'";
            mysqli_query($con, $sql_u);
            $sql_u = "update S_User SET Tbl_Cnt = ".$tbl_cnt." where S_ID = '".$post_s_id."'";
            mysqli_query($con, $sql_u);
        }
        $return_string = "S:SUCCESS";
    }
    

    header("Content-Type:application/json");
    echo json_encode(array('_STRING'=>$return_string), JSON_PRETTY_PRINT);


}
mysqli_close($con); // db close

?>