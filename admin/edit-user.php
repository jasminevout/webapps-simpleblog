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
	<title>Admin - Edit User | Blog de Omnibus</title>

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

	<h2 class="text-dark">Edit User</h2>


	<?php
	// process submitted form
	if(isset($_POST['submit'])){
		// collect form data
		extract($_POST);
		// basic form validation
		if($username ==''){
			$error[] = 'Please enter the username.';
		}
		// check password if entered
		if( strlen($password) > 0){
			if($password ==''){
				$error[] = 'Please enter the password.';
			}
			if($passwordConfirm ==''){
				$error[] = 'Please confirm the password.';
			}
			if($password != $passwordConfirm){
				$error[] = 'Passwords do not match.';
			}
		}
		if($email ==''){
			$error[] = 'Please enter the email address.';
		}
		if(!isset($error)){
			try {
				if(isset($password)){
					// if password entered, update database
					$hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);
					$stmt = $db->prepare('UPDATE blog_members SET username = :username, password = :password, email = :email WHERE memberID = :memberID') ;
					$stmt->execute(array(
						':username' => $username,
						':password' => $hashedpassword,
						':email' => $email,
						':memberID' => $memberID
					));
				} else {
					// if no password entered, update database
					$stmt = $db->prepare('UPDATE blog_members SET username = :username, email = :email WHERE memberID = :memberID') ;
					$stmt->execute(array(
						':username' => $username,
						':email' => $email,
						':memberID' => $memberID
					));
				}
				
				// redirect to index page
				header('Location: users.php?action=updated');
				exit;
			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
		}
	}
	?>

	<?php
	// check for errors, display errors
	if(isset($error)){
		foreach($error as $error){
			echo $error.'<br />';
		}
	}
		try {
			$stmt = $db->prepare('SELECT memberID, username, email FROM blog_members WHERE memberID = :memberID') ;
			$stmt->execute(array(':memberID' => $_GET['id']));
			$row = $stmt->fetch(); 
		} catch(PDOException $e) {
		    echo $e->getMessage();
		}
	?>

	<!-- EDIT USER FORM -->
	<form action='' method='post'>
		<input type='hidden' name='memberID' value='<?php echo $row['memberID'];?>' class="form-control">
		<!-- Username -->
		<p><label>Username</label><br />
		<input type='text' name='username' value='<?php echo $row['username'];?>' class="form-control"></p>
		<!-- Password -->
		<p><label>New Password</label><br />
		<input type='password' name='password' value='' class="form-control"></p>
		<!-- Confirm Password -->
		<p><label>Confirm Password</label><br />
		<input type='password' name='passwordConfirm' value='' class="form-control"></p>
		<!-- Email -->
		<p><label>Email</label><br />
		<input type='text' name='email' value='<?php echo $row['email'];?>' class="form-control"></p>
		<!-- Submit Button -->
		<p><input type='submit' name='submit' value='Update User' class="btn btn-dark add-btn"></p>
	</form>

</div> <!-- end .container -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>