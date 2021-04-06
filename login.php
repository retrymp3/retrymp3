<?php

session_start();
header('location:login.php');

// Database connection
$conn = mysqli_connect('localhost','root','');
mysqli_select_db($conn, 'auth');

$name = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$re_password = $_POST['re_password'];

$s = "select * from signup where name='$name' && password='$password'";
$result=mysqli_query($conn, $s);
$num=mysqli_num_rows($result);

if($num == 1){
	header('location:shop.html');
} else {
	header('location:login.html');
}
?>
