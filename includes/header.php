<html>
	<head>
		<title>UofU ACM</title>
	    <link href="stylesheet.css" rel="stylesheet" type="text/css"/>
	</head>
	<body>
		<div class="header">
				<ul id="data">	
				   <li id="logo"><img src="Ulogo.png"/></li>
				   <li id="title"><h2>University of Utah ACM</h2></li>
				   <li id="right">
				   		<?php if (!isset($_SESSION['username'])) { 
				   			echo "Welcome Guest";
				   			} else { 
				   			echo "Welcome " . $_SESSION['username']; 
							} ?>
				   	</li>
				</ul>
		<div id="navbar">
			<nav>
				<a href="index.php">Home</a>
				<a href="about.php">About</a>
				<a href="events.php">Events</a>
				<a href="members.php">Members</a>
				<a href="#contact.php">Contact</a>
				<?php if (isset($_SESSION['user_id'])) { ?>
					<a href="logout.php">Logout</a>
				<?php } else { ?>
					<a href="login.php">Login</a>
				<?php } ?>
				
			</nav>
		</div>
		</div>