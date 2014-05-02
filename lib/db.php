<?php
$host='128.173.41.131';
$port='5432';
$dbname='sqlkillers';
$user='pula2000';
$password='123456';

$conn_string = "port=$port dbname=$dbname user=$user password=$password";

$conn = pg_connect($conn_string) or die('fail to connect:'.pg_last_error());
?>
