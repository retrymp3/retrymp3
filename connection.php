<?php

$dbhost = "localhost"; /* host name */
$dbuser = "root"; /* username */
$dbpass = ""; /* password(default is '') */
$dbname = "auth"; /* database name */

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname)) /* checks to see if it doesn't connect to the database */
{

	die("failed to connect!"); /* error message */
}
