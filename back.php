<?php
	
  //$postData = file_get_contents('php://input');
  /*
  $postJSON = json_decode($postData);
  $JSON_usr = "";
  $JSON_pw = "";
  foreach($postJSON->data as $item) {
    if($item->username) {
      $JSON_usr = $item;
    }
    if($item->password) {
      $JSON_pw = $item;
    }
  
  */
 
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
		print "<br>Query failed...";*/
	

	$loginValid = false; //flag for login

	$query = "SELECT * FROM `cs490_logins` WHERE `username` = '$username' ";
	$rehash_query = mysqli_query($sql_conn, "UPDATE `cs490_logins` SET `password` = '$password_hashed' WHERE `username` = '$username'");
	$get_hash = mysqli_fetch_array($query, MYSQLI_ASSOC); //getter for hashed password in db
	$ifPassValid = password_verify($password_hashed, $get_hash['password']); 
	//echo $get_hash'password'];
	if($result=mysqli_query($sql_conn, $query)) { //check if query works
		if(mysqli_num_rows($result) > 0) { //check if username and password matches and exists
			//echo "<br>Found you in the database!";
			if($ifPassValid)
				$loginValid = true;
			if(password_needs_rehash($password_hashed, PASSWORD_BCRYPT)) { //rehash password since it's void after 'password_verify'
				$password_hashed = password_hash($password, PASSWORD_BCRYPT);
				mysqli_query($sql_conn, $rehash_query);
				echo "<br>password has been rehashed...";
			}
		}
	}
    //creating the json to send
	 $responseObj = [ 
	 	[
	   msg => 'Invalid Login!' //by default is invalid login
		]
	];
  $loginValid = true;
  //if valid, then send msg
	if ($loginValid) {
     foreach ($responseObj as &$str) {
       $str = strtr($responseObj[msg], 'Invalid Login!', 'Valid Login!');
     }
	}

	$myJSON = json_encode($responseObj);
	
	echo $myJSON;
 
 
?>
