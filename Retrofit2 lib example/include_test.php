<?php

include 'mysql_connect.php'; // mysql 접속

$sql = "SELECT * FROM UserInfo WHERE ID = id123";

$result = mysqli_query($con, $sql);
$res = array();

header("Content-Type:application/json");
if($row = mysqli_fetch_array($result)){ // 해당 row 있으면 관련 정보 다시 전송
	
	echo $row[0];
}


mysqli_close($con); // db close

?>