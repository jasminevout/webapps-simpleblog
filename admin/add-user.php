<?php
//include config
require_once('../includes/config.php');
//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Admin - Add User | Blog de Omnibus</title>

	<!-- CSS -->
	<link rel="stylesheet" href="../normalize.css">
	<link rel="stylesheet" href="../main.css">

	<!-- FONTS -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,700|Martel&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
	<?php include('menu.php');?>
	<hr>
	<p><a href="users.php">← Back to User Index</a></p>
	<hr>

	<h2 class="text-dark">Add User</h2>

	<?php
	// process submitted form
	if(isset($_POST['submit'])){
		// collect form data
		extract($_POST);
		// basic form validation
		if($username ==''){
			$error[] = 'Please enter the username.';
		}
		if($password ==''){
			$error[] = 'Please enter the password.';
		}
		if($passwordConfirm ==''){
			$error[] = 'Please confirm the password.';
		}
		if($password != $passwordConfirm){
			$error[] = 'Passwords do not match.';
		}
		if($email ==''){
			$error[] = 'Please enter the email address.';
		}
		if(!isset($error)){
			$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);
			try {
				// insert into database
				$stmt = $db->prepare('INSERT INTO blog_members (username,password,email) VALUES (:username, :password, :email)') ;
				$stmt->execute(array(
					':username' => $username,
					':password' => $hashedpassword,
					':email' => $email
				));
				// redirect to index page
				header('Location: users.php?action=added');
				exit;
			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
		}
	}
	// check for errors, display errors
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
	?>

	<!-- ADD USER FORM -->
	<form action='' method='post'>
		<!-- Username -->
		<p><label>Username</label><br />
		<input type='text' name='username' value='<?php if(isset($error)){ echo $_POST['username'];}?>' class="form-control"></p>
		<!-- Password -->
		<p><label>Password</label><br />
		<input type='password' name='password' value='<?php if(isset($error)){ echo $_POST['password'];}?>' class="form-control"></p>
		<!-- Confirm Password -->
		<p><label>Confirm Password</label><br />
		<input type='password' name='passwordConfirm' value='<?php if(isset($error)){ echo $_POST['passwordConfirm'];}?>' class="form-control"></p>
		<!-- Email -->
		<p><label>Email</label><br />
		<input type='text' name='email' value='<?php if(isset($error)){ echo $_POST['email'];}?>' class="form-control"></p>
		<!-- Submit Button -->
		<p><input type='submit' name='submit' value='Add User' class="btn btn-dark add-btn"></p>

	</form>
</div> <!-- end .container -->