<?php 

session_start();

	include("connection.php");
	include("functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$user_name = $_POST['username'];
		$password = $_POST['password'];

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
		{

			//read from database
			$query = "select * from signup where username = '$user_name' limit 1";
			$result = mysqli_query($con, $query);

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password)
					{

						$_SESSION['user_id'] = $user_data['user_id'];
						header("Location: shop.php");
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
	  <form method="post">
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

