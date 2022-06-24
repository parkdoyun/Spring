<?php
// mysql 계정 접속
$host = '3.36.230.124';
$username = 'user_sp';
$password = 'pwd123';
$dbName = 'spring';
$con = mysqli_connect($host, $username, $password, $dbName);

mysqli_query($con, 'set session character_set_connection=utf8;');
mysqli_query($con, 'set session character_set_results=utf8;');
mysqli_query($con, 'set session character_set_client=utf8;');

$post_id = $_GET['post_id']; // id get

$sql = "SELECT * FROM UserInfo WHERE ID = '".$post_id."'"; // query

$result = mysqli_query($con, $sql);

$res = array();

header("Content-Type:application/json");
if($row = mysqli_fetch_array($result)){ // 해당 row 있으면 관련 정보 다시 전송
	
	echo json_encode(array('_ID'=>$row[0], '_PWD'=>$row[1], '_Yn'=>$row[2]), JSON_PRETTY_PRINT);
}

mysqli_close($con); // db close



?>
