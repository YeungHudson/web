<?php
	
  	$postData = file_get_contents('php://input');
  
  	$postJSON = json_decode($postData);
  	$JSON_usr = "";
  	$JSON_pw = "";
  	foreach($postJSON->data as $item) {
    		if($item->Username) {
      			$JSON_usr = $item;
    		}
    		if($item->Password) {
     			$JSON_pw = $item;
    	}
 
	$dbhost = "sql1.njit.edu";
		//logins here
	$sql_conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	if(!$sql_conn) {
		die("Cannot connect with DB: " . mysqli_connect_error());
	}	

	$username = mysqli_real_escape_string($sql_conn, $_POST['Username']);
	$password = mysqli_real_escape_string($sql_conn, $_POST['Password']);
	$correct_hash = '$2y$10$q4PuAl96JL/RBInOG1xlmOdwdxliJk3T0rpaKRleKomIeXN5nTMzu';
	/*if(mysqli_query($sql_conn, "INSERT INTO cs490_logins(username,password) VALUES('$username','$password_hashed')") == true) {
		print "<br>successfully inserted a row!";
	}
	else
		print "<br>Query failed...";*/
	

	$loginValid = false; //flag for login

	$query = "SELECT `password` FROM `cs490_logins` WHERE `username` = '$username' ";
	$rehash_query = mysqli_query($sql_conn, "UPDATE `cs490_logins` SET `password` = '$password_hashed' WHERE `username` = '$username'");
	//$get_hash = mysqli_fetch_array($query, MYSQLI_ASSOC); //getter for hashed password in db
	$ifPassValid = password_verify($password, $correct_hash);
	if($result=mysqli_query($sql_conn, $query)) { //check if query works
		if(mysqli_num_rows($result) > 0) { //check if username and password matches and exists
			echo "<br>Found you in the database!";
			if($ifPassValid) {
				$loginValid = true;
				echo "Logged in!";
		        }
		} 
		else
			echo "fail!";
	}
  /*  //creating the json to send
	 $responseObj = [ 
	 	[
	   msg => 'Invalid Login!' //by default is invalid login
		]
	];
  $loginValid = true;
  if valid, then send msg
	if ($loginValid) {
     $responseObj = [
			[	
				msg => 'Valid Login!' //by default is invalid login
	        	]
        	];
	}

	$myJSON = json_encode($responseObj);
	
	echo $myJSON;
*/ 
 
?>
