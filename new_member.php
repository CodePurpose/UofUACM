<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php require_once("includes/session.php"); ?>
<?php
	$username="";
	$lastname="";
	$email="";
	$errors = array();
	// Form Validation
	$user_fields = array('firstname', 'lastname', 'email', 'username', 'password', 'linkedin', 'grade', 'gender');
	foreach($user_fields as $fieldname) {
		if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
			$errors[] = $fieldname;
		}
	}
	if(isset($_POST['password'])){
		//if(strcomp($_POST['password'],$_POST['confirmpassword'])){
			//$errors[] = "Passwords did not match";
		//}
	}
	if(!empty($errors)){
		redirect_to("registration.php");
		echo "Please fix the following errors";
		echo  "<ul>";
		foreach ($errors as $key => $error){
			echo "<li>{$error}</li>";
		}
		echo "</ul>";
	}

?>
<?php
	$firstname = mysql_prep($_POST['firstname']);
	$lastname = mysql_prep($_POST['lastname']);
	$email = mysql_prep($_POST['email']);
	$username = mysql_prep($_POST['username']);
	$password = mysql_prep($_POST['password']);
	$linkedin = mysql_prep($_POST['linkedin']);
	$grade = mysql_prep($_POST['grade']);
	$gender = mysql_prep($_POST['gender']);
	$hashed_password = sha1($password);
?>

<?php
	if(empty($errors)){
	//This query is to confirm that this user does not already exist.	
	$query = sprintf("SELECT * FROM registration WHERE email='%s'", 
		mysql_real_escape_string($email));
		
		$result = mysql_query($query);
	
	if (mysql_num_rows($result) > 0) 	{
			//User already exists
			//send some JSON back to the app
			echo "You are already a member";

			} else {
			//Create new user	
			$query = "INSERT INTO registration (
					firstname, 
					lastname, 
					email, 
					username,
					hashed_password,
					linkedin,
					grade, 
					gender 
					) VALUES (
					'{$firstname}', 
					'{$lastname}', 
					'{$email}', 
					'{$username}',
					'{$hashed_password}',
					'{$linkedin}',
					'{$grade}', 
					'{$gender}'
				)";
			$result = mysql_query($query, $connection);
			}

			if (mysql_affected_rows() == 1) {
				//Success!
				//This query is to pull the newly created user id
				$query = sprintf("SELECT * FROM registration WHERE firstname='%s' AND lastname='%s' AND email='%s'", 
				mysql_real_escape_string($firstname), 
				mysql_real_escape_string($lastname),
				mysql_real_escape_string($email));

				$result = mysql_query($query);
					if (!$result) {
						$message = 'Invalid query: ' . mysql_error() . "\n";
						$message .= 'Whole query: ' . $query;
						die($message);
				
					} else {
					// send some JSON back to the app
					while ($row = mysql_fetch_assoc($result)) {
						echo $row['user_id'];
						$user_id = $row['user_id'];
						$username = $row['username'];
						}
						$_SESSION['user_id']=$user_id;
						$_SESSION['username']=$username;
					}
			} else {
					//Display error message.
					echo "<p>User setup failed.</p>";
					echo "<p>" . mysql_error() . "</p>";
				}	
				echo "Congratulations! You are now a member.";
				}
?>
<?php mysql_close($connection); ?>