<html>
<body>

<?php
        #this is the submitform.php where it sends the login info into the database

	print "logging you into the database...";
        
	$dbhost = "sql1.njit.edu";
	$dbuser = "hy276";
	$dbpass = "tempPassword"; //not my real password : ^) 
	$dbname = "hy276";
	$sql_conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	if(!$sql_conn) {
		die("Cannot connect with DB: " . mysqli_connect_error());
		}
	print "<br>connected!";
	$username = $_POST['username'];
	//echo $username;
	$password = password_hash( $_POST['password'], PASSWORD_DEFAULT);
	/*if(mysqli_query($sql_conn, "INSERT INTO cs490_logins(username,password) VALUES('$username','$password')") == true) {
		print "<br>successfully inserted a row!";
	}
	else
		print "<br>Query failed...";
	*/

	
?>  
</body>
</html>
