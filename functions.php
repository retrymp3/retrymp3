<?php

function check_login($con) /* function to check if the user is logged in */
{
	if(isset($_SESSION['user_id'])) /* checks if session value is set as 'user_id' */
	{

		$id = $_SESSION['user_id']; /* assigns the session value to $id */
		$query = "select * from signup where user_id = '$id' limit 1"; /* used to check if user_id is in the database */

		$result = mysqli_query($con,$query); /* performs the query against the database */
		if($result && mysqli_num_rows($result) > 0) /* checks to see if the result is true and there is data present in the database by returning the number of rows */
		{

			$user_data = mysqli_fetch_assoc($result); /* fetches the coresponding user data as an associative array. */
			return $user_data;
		}
	}

	//redirect to login page
	header("Location: login.php");
	die;

}

function random_num($length) /* function for generating a random number */
{

	$text = "";
	if($length < 5)
	{
		$length = 5; /* if the given len less than 5, length set as 5 */
	}

	$len = rand(4,$length); /* generates random num b/w 4 and $length so user_id won't be the same length everytime */

	for ($i=0; $i < $len; $i++) { 


		$text .= rand(0,9); /* adds random numbers between 0 and 9 with the length of $length var */
	}

	return $text;
}
