<?php 
session_start(); /* creates a session or resumes the current one*/

	include("connection.php"); /* for including the php file in this page itself*/
	include("functions.php"); 


	if($_SERVER['REQUEST_METHOD'] == "POST") /* checks if the reqest method is post*/
	{
		//post request has been recieved
		$user_name = $_POST['username']; /* assigns the username post data to $user_name */
		$email = $_POST['email']; /* assigns the email post data to $email */
		$password = $_POST['password']; /* assigns the password post data to $password */

		if(!empty($user_name) && !empty($email) && !empty($password) && !is_numeric($user_name)) /* checking wether the variables are empty ot not, also wether it is numeric */
		{

			//save to database
			$user_id = random_num(20); /* generating random number with len, 20 for user_id */
			$query = "insert into signup (user_id,username,email,password) values ('$user_id','$user_name','$email','$password')"; /* inserting values into the database */

			mysqli_query($con, $query); /* conects and performs the query against the database */

			//redirect to login page
			header("Location: login.php");
			die;
		}else
		{
			echo "Please enter some valid information!";
		}
	}
?>


<!DOCTYPE html>
<html>
<head>
<title> Sign-up </title>
	<link rel="stylesheet type="text/css" href="./style.css">
<body>
	<header>
	<div class="main">
	 <ul>
	 	<li><a href="shop.php">Shop</a></li>
		<li><a href="index.html">Home</a></li>
	 	<li><a href="login.php">Login</a></li>
	 	<li class="active"><a href="sign-up.php">Signup</a></li>
	 	<li><a href="about.html">About</a></li>
	 </ul>
	</div>
	<div class="log">
	<img src="./img/simpson-sad.jpeg" class="avatar">
	<h1> Sign-up </h1>
	
	<div id="box">
	  <form method="post"> <! setting the request method as post >
		<p>Username</p>
		<input id="username" type="text" name="username" placeholder="Enter Username"  />

		<p>Email</p>
		<input id="email" type="text" name="email" placeholder="Enter Email" />
		<p>Password</p>
		<input id="password" type="password" name="password" placeholder="Enter Password" />
		<input type="submit" name="submit" value="submit">
	   </form>
	   </div>

</body>
</head>
</html>
