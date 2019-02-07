<?php
	
	$dbhost = "sql1.njit.edu";
	$dbuser = "hy276";
	$dbpass = "HY9Co7Qkq";
	$dbname = "hy276";
	$sql_conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	if(!$sql_conn) {
		die("Cannot connect with DB: " . mysqli_connect_error());
		}

	$username = mysqli_real_escape_string($sql_conn, $_POST['username']);

	$password = mysqli_real_escape_string($sql_conn, $_POST['password']);
	$password_hashed = password_hash($password, PASSWORD_BCRYPT);
	/*if(mysqli_query($sql_conn, "INSERT INTO cs490_logins(username,password) VALUES('$username','$password_hashed')") == true) {
		print "<br>successfully inserted a row!";
	}
	else
		print "<br>Query failed...";
	*/

	$loginValid = false; //flag for login

	$query = "SELECT * FROM `cs490_logins` WHERE `username` = '$username' ";
	//$pw_query = "SELECT * FROM `cs490_logins` WHERE `password` = '$password' ";
	$rehash_query = mysqli_query($sql_conn, "UPDATE `cs490_logins` SET `password` = '$password_hashed' WHERE `username` = '$username'");
	$get_hash = mysqli_fetch_array($query, MYSQLI_ASSOC); //getter for hashed password in db
	$ifPassValid = password_verify($password_hashed, $get_hash['password']); 
	//echo $get_hash'password'];
	if($result=mysqli_query($sql_conn, $query)) { //check if query works
		if(mysqli_num_rows($result) > 0) { //check if username and password matches and exists
			echo "<br>Found you in the database!";
			if($ifPassValid)
				$loginValid = true;  
			else
				continue;
			if(password_needs_rehash($password_hashed, PASSWORD_BCRYPT)) { //rehash password since it's void after 'password_verify'
				$password_hashed = password_hash($password, PASSWORD_BCRYPT);
				mysqli_query($sql_conn, $rehash_query);
				echo "<br>password has been rehashed...";
			}
		}
		else {
			continue; //invalid login
		}
	}

	 $responseObj = [
	 	[
		"loginValid" => "false",
		"msg" => "",
		]
	];

	if ($loginValid) {
	 	$responseObj->loginValid = true;
		$responseObj->msg = "Valid login!";
	}
	else {
		$responseObj->msg = "Invalid login!";
	}

	$myJSON = json_encode($responseObj);
	
	echo $myJSON
?>