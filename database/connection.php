<?php

$servername="localhost";
$username="root";
$password="";
$dbname="insurance";
$conn=new mysqli($servername, $username, $password, $dbname,3306);
if($conn->connect_error){
    die("connection failed:".$conn->connect_error);
}
echo "";

?>