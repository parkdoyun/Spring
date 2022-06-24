<?php

include 'mysql_connect.php';

// 테이블 대수, 테이블 번호 배열, 테이블 좌표 입력 받아 등록

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
  
    $post_tbl_n = $_GET['post_tbl_n'];
    $post_tbl_idx = $_GET['post_tbl_idx'];
    $post_arr_x = $_GET['post_arr_x'];
    $post_arr_y = $_GET['post_arr_y'];
    $S_ID = $_GET['post_s_id'];
    
    $return_string = "F:FAILED"; // 보낼 string, default : 가입 실패



    $ok_check = 1;
    for($i=0; $i < $post_tbl_n; $i = $i+1)
    {        
        $sql = "INSERT INTO Tbl VALUES(".$post_tbl_idx[$i].",'".$S_ID."',0, 0, ".$post_arr_x[$i].",".$post_arr_y[$i].")";
        $result_t = mysqli_query($con, $sql);
        if($result_t)
        { // insert 성공
            ;
        }
        else
        { // update
            
            $sql2 = "update Tbl SET Pos_X = ".$post_arr_x[$i]." where Tbl_ID = ".$post_tbl_idx[$i]." and S_ID = '".$S_ID."'";
            $result_x = mysqli_query($con, $sql2);
            $sql3 = "update Tbl SET Pos_Y = ".$post_arr_y[$i]." where Tbl_ID = ".$post_tbl_idx[$i]." and S_ID = '".$S_ID."'";
            $result_y = mysqli_query($con, $sql3);
            if($result_x and $result_y)
            {
                ;
            }
            else
            {
                $return_string = "F:Tbl ".$post_tbl_idx[i]." UPDATE IS FAILED";
                $ok_check = 0;
                break;
            }
        }
    }
    if($ok_check == 1) $return_string = "S:SUCCESS";

    header("Content-Type:application/json");
    echo json_encode(array('_STRING'=>$return_string), JSON_PRETTY_PRINT);


}
mysqli_close($con); // db close

?>