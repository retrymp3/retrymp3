<?php 

session_start(); /* creates a session or resumes the current one*/

	include("connection.php"); /* for including the php file in this page itself*/
	include("functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST") /* checks if the reqest method is post*/
	{
		//something was posted
		$user_name = $_POST['username']; /* assigns the username post data to $user_name */
		$password = $_POST['password']; /* assigns the password post data to $password */

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
		{

			//read from database
			$query = "select * from signup where username = '$user_name' limit 1"; /* query to check if the username is in the table */
			$result = mysqli_query($con, $query); /* connects and queries the $query */

			if($result) /* checking if it returned data */
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password) /* checks if the password in db is same as the given password */
					{

						$_SESSION['user_id'] = $user_data['user_id']; /* since the check_login() checks if the session id is set, we have to assign it in the login page */
						header("Location: shop.php"); /* since the */
						die;
					}
				}
			}
			
			echo "wrong username or password!";
		}else
		{
			echo "wrong username or password!";
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
<title> Login </title>
	<link rel="stylesheet type="text/css" href="./style.css">
<body>
	<header>
	<div class="main">
	 <ul>
	 	<li><a href="shop.php">Shop</a></li>
		<li><a href="index.html">Home</a></li>
	 	<li class="active"><a href="login.php">Login</a></li>
	 	<li><a href="sign-up.php">Signup</a></li>
	 	<li><a href="about.html">About</a></li>
	 </ul>
	</div>
	<div class="log">
	<img src="./img/simpson-sad.jpeg" class="avatar">
	<h1> Login </h1>
	<div id="box">
	  <form method="post"> <! setting the request method as post >
		  <p>Username</p>
		  <input id="username" type="text" name="username" placeholder="Enter Username"  />
		  <p>Password</p>
		  <input id="password" type="password" name="password" placeholder="Enter Password" />
		  <input type="submit" value="Login" id="button">
	</form>
	</div>

</body>
</head>
</html>
