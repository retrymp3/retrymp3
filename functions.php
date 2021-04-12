<?php

function check_login($con) /* function to check if the user is logged in */
{

	if(isset($_SESSION['user_id'])) /* checks if session value is set as 'user_id' */
	{

		$id = $_SESSION['user_id']; /* assigns the session value to $id */
		$query = "select * from signup where user_id = '$id' limit 1"; /* checking the database. limit 1 will return the first record.*/

		$result = mysqli_query($con,$query); /* performs the query against the database */
		if($result && mysqli_num_rows($result) > 0) /* checks to see if the result is positive and there is data present inthe database by returning the number of rows */
		{

			$user_data = mysqli_fetch_assoc($result); /* fetches a result row as an associative array. */
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
		$length = 5;
	}

	$len = rand(4,$length);

	for ($i=0; $i < $len; $i++) { 


		$text .= rand(0,9);
	}

	return $text;
}
