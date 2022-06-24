<?php

include 'mysql_connect.php';

// 사용자 ID, 매장 정보 입력 빋아서 사업자로 변경 및 매장 정보 업데이트

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{     
      
    $post_id = $_GET['post_id'];
    $S_ID = $_GET['post_s_id'];    
    $post_o_time = $_GET['post_o_time'];
    $post_c_time = $_GET['post_c_time'];
    $post_b_num = $_GET['post_b_num'];    
    
    $return_string = "F:FAILED"; // 보낼 string, default : 가입 실패

    
    // UserInfo 정보 수정 (점주 등록)
    $sql = "update UserInfo SET Busi_Yn = 1 where ID = '".$post_id."'";
    $result = mysqli_query($con, $sql);

    if($result) // UserInfo 수정 성공
    {         
        // skeeper 등록        
        $sql = "INSERT INTO Skeeper VALUES('".$post_id."','".$S_ID."','".$post_b_num."')";
        $result3 = mysqli_query($con, $sql);

        if($result3) // insert 성공 못 했을 시
        {
            // Store 수정
            $sql = "update Store SET O_TIME = '".$post_o_time."' where S_ID = '".$S_ID."'";
            $result4 = mysqli_query($con, $sql);
            $sql = "update Store SET C_TIME = '".$post_c_time."' where S_ID = '".$S_ID."'";
            $result41 = mysqli_query($con, $sql);

            if($result4 && $result41)
            {
                // S_Skeeper 항목 만들어 놓기
                $sql = "INSERT INTO S_Skeeper VALUES('".$S_ID."','".$post_id."','', '')";
                $result5 = mysqli_query($con, $sql);
                if($result5) // insert 성공 못 했을 시
                {
                    $return_string = "S:SUCCESS";                          
                }
                else{
                    $return_string = "F:S_Skeeper INSERT IS FAILED";
                    // break;
                }

            }
            else
            {
                $return_string = "F:STORE UPDATE IS FAILED";
                // break;        
            }
        }
        else{
            $return_string = "F:Skeeper INSERT IS FAILED";
            // break;
        }        

    }
    else{
        $return_string = "F:USER ID NOT FOUND";
    }

    header("Content-Type:application/json");
    echo json_encode(array('_STRING'=>$return_string), JSON_PRETTY_PRINT);


}
mysqli_close($con); // db close

?>