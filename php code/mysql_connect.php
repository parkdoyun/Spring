<?php
// mysql 계정 접속하는 공통 php
$host = '3.36.230.124';
$username = 'parkdoyun';
$password = 'pwd123';
$dbName = 'spring';
$con = mysqli_connect($host, $username, $password, $dbName);

mysqli_query($con, 'set session character_set_connection=utf8;');
mysqli_query($con, 'set session character_set_results=utf8;');
mysqli_query($con, 'set session character_set_client=utf8;');

?>