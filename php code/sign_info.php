<?php

include 'mysql_connect.php';

// 회원가입 정보 db 전송
if($_SERVER['REQUEST_METHOD'] == 'GET') // GET으로 받았을 때만
{
    header("Content-Type:application/json");
    
    $post_id = $_GET['post_id'];
    $post_name = $_GET['post_name'];
    $post_pw = $_GET['post_pw'];
    
    $return_string = "F:FAILED"; // 보낼 string, default : 가입 실패

    // UserInfo, D_UserInfo 테이블 둘다 insert

    // UserInfo insert
    $sql = "INSERT INTO UserInfo VALUES('".$post_id."','".$post_pw."',0)";
    $result = mysqli_query($con, $sql);

    if($result) // UserInfo insert 성공해야 D_UserInfo insert 가능
    {
        // D_UserInfo insert
        $sql2 = "INSERT INTO D_UserInfo VALUES('".$post_id."','".$post_name."',0)";
        $result2 = mysqli_query($con, $sql2);

        if($result2)
        {
            $return_string = "S:SUCCESS"; // 둘다 성공 시
        }
    }
    

    header("Content-Type:application/json");
    echo json_encode(array('_STRING'=>$return_string), JSON_PRETTY_PRINT);


}
mysqli_close($con); // db close

?>