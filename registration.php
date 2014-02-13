
<?php 
	require_once("includes/session.php");
	require_once("includes/connection.php"); 
	require_once("includes/functions.php"); 
	$errors = array();
	if(isset($_POST['submit'])){
		$firstname = $_POST["firstname"];
		$lastname = $_POST["lastname"];
		$email = $_POST["email"];
		$username = $_POST["username"];
		$linkedin = $_POST["linkedin"];
		$output = "";
		$message = "";
		$user_fields = array('firstname', 'lastname', 'email', 'username', 'password', 'linkedin', 'grade', 'gender');
		foreach($user_fields as $fieldname) {
			if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
				$errors[$fieldname] = $fieldname . " can't be blank";
			}
		}
		if(isset($_POST['password'])){
			if($_POST['password']!=$_POST['confirmpassword']){
				$errors['password'] = "Passwords did not match";
			}
		}
	
		if(empty($errors)){
			$firstname = mysql_prep($_POST['firstname']);
			$lastname = mysql_prep($_POST['lastname']);
			$email = mysql_prep($_POST['email']);
			$username = mysql_prep($_POST['username']);
			$password = mysql_prep($_POST['password']);
			$linkedin = mysql_prep($_POST['linkedin']);
			$grade = mysql_prep($_POST['grade']);
			$gender = mysql_prep($_POST['gender']);
			$hashed_password = sha1($password);
		}
	}
	else{
		$firstname="";
		$lastname="";
		$email="";
		$username="";
		$linkedin="";
		$output="";
		$message = "Please fill out form to register";
	}
?>
<?php
if(isset($_POST['submit'])){
	if(empty($errors)){
	//This query is to confirm that this user does not already exist.	
	$query = sprintf("SELECT * FROM registration WHERE email='%s'", 
		mysql_real_escape_string($email));
		
		$result = mysql_query($query);
	
	if (mysql_num_rows($result) > 0) 	{
			//User already exists
			//send some JSON back to the app
			$message = "You are already a member";
		}
	 else {
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
			$message = "Congratulations! You are now a member.";
			
			

			if (mysql_affected_rows() == 1) {
				//Success!
				//This query is to pull the newly created user id
				$query = sprintf("SELECT * FROM registration WHERE firstname='%s' AND lastname='%s' AND email='%s'", 
				mysql_real_escape_string($firstname), 
				mysql_real_escape_string($lastname),
				mysql_real_escape_string($email));

				$result = mysql_query($query);
					if (!$result) {
						$querymessage = 'Invalid query: ' . mysql_error() . "\n";
						$querymessage .= 'Whole query: ' . $query;
						die($querymessage);
				
					} else {
					// send some JSON back to the app
					while ($row = mysql_fetch_assoc($result)) {
						$user_id = $row['user_id'];
						$username = $row['username'];
						}
						$_SESSION['user_id']=$user_id;
						$_SESSION['username']=$username;
					}
			} else {
					//Display error message.
					$output = "<p>User setup failed.</p>";
					$output .= "<p>" . mysql_error() . "</p>";
			}
				}	
				}
				}
?>
<?php 
	$output ="";
	
	if (!empty($errors)) {
		
	  $output .= "<div class=\"error\">";
	  $output .= "Please fix the following errors:";
	  $output .= "<ul>";
	  foreach ($errors as $key => $error) {
	    $output .= "<li>{$error}</li>";
	  }
	  $output .= "</ul>";
	  $output .= "</div>";
	 
	}
?>
<?php include("includes/header.php"); ?>

<!DOCTYPE html>
<html>
<head> 
	<title>Registration</title>
	<link href="stylesheet.css" rel="stylesheet" type="text/css"
</head>

<body>
<!-- <div class="header">
	<h2>University of Utah ACM</h2>
</div> -->
<div class="firstText">
<?php echo $message; ?>
<?php echo $output; ?>

<form action="registration.php" method="post">
	
	First name: <input type="text" name="firstname" value=<?php echo $firstname ?>> <br>
	Last name: <input type="text" name="lastname" value=<?php echo $lastname ?>> <br>
	Email Address: <input type="text" name="email" value=<?php echo $email ?>> <br>
	Username: <input type="text" name="username" value=<?php echo $username ?>><br>
	Password: <input type="password" name="password"><br>
	Confirm Password: <input type="password" name="confirmpassword"><br>
	LinkedIn Profile: <input type="text" name="linkedin" value=<?php echo $linkedin ?>><br>
	Grade Status: <select name="grade">
			<option value="Freshman">Freshman</option>
			<option value="Sophomore">Sophomore</option>
			<option value="Junior">Junior</option>
			<option value="Senior">Senior</option>
		      </select><br>
	Gender: Male<input type="radio" name="gender" value="male">
			Female<input type="radio" name="gender" value="female"><br>
	<input type="submit" name = "submit" value="Add User" />
	
</form>

</div>


<?php mysql_close($connection); ?>
</body>
<footer><div id="footer"></div></footer>
</html>










