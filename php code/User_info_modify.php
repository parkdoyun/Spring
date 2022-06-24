<?php

include 'mysql_connect.php';

// 사용자 정보 수정

if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    header("Content-Type:application/json");   
      
    $post_id = $_GET['post_id'];
    $post_pw = $_GET['post_pw'];
    $post_name = $_GET['post_name'];
    
    $return_string = "F:FAILED"; // 보낼 string, default : 가입 실패

    
    // UserInfo 정보 수정
    $sql = "update UserInfo SET PWD = '".$post_pw."' where ID = '".$post_id."'";
    $result = mysqli_query($con, $sql);

    if($result) // UserInfo 수정 성공 -> D_UserInfo 수정
    {         
        $sql2 = "update D_UserInfo SET Name = '".$post_name."' where ID = '".$post_id."'";
        $result2 = mysqli_query($con, $sql2); 
        if($result2){
            $return_string = "S:SUCCESS";
        }
        else $return_string = "F:D_UserInfo cannot modify";
    }
    

    header("Content-Type:application/json");
    echo json_encode(array('_STRING'=>$return_string), JSON_PRETTY_PRINT);


}
mysqli_close($con); // db close

?>