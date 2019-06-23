<?php
//include config
require_once('../includes/config.php');
//check login status, redirect to login page if not logged in
if( $user->is_logged_in() ){ header('Location: index.php'); } 
?>

<!-- HEAD/HEADER -->
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Admin - Login | Blog de Omnibus</title>

	<!-- CSS -->
	<link rel="stylesheet" href="../normalize.css">
	<link rel="stylesheet" href="../main.css">

	<!-- FONTS -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,700|Martel&display=swap" rel="stylesheet">
</head>
<body>
	
<div class="container">
	<div class="row">
		<div class="col">
			<h1 class="page-title">
				<a href="../index.php">
					<i class="fas fa-book-reader"></i> Blog de Omnibus
				</a>
			</h1>
			<hr/>
		</div> <!-- end .col -->
	</div> <!-- end .row -->
</div> <!-- end .container -->

<div class="container">
	<div id="login">

		<?php
		//process login form if submitted
		if(isset($_POST['submit'])){
			$username = trim($_POST['username']);
			$password = trim($_POST['password']);
			
			if($user->login($username,$password)){ 
				//if successful, send user to admin/index.php
				header('Location: index.php');
				exit;
			
			} else {
				//if unsuccessful, return error message
				$message = '<p class="error">Wrong username or password</p>';
			}
		}//end if submit
		if(isset($message)){ echo $message; }
		?>

		<!-- LOGIN FORM -->
		<form action="" method="post" class="login-form">
		<p><label>Username</label><input type="text" name="username" value="" class="form-control" /></p>
		<p><label>Password</label><input type="password" name="password" value="" class="form-control" /></p>
		<p><label></label><input type="submit" name="submit" value="Login" class="btn btn-dark submit-btn"/></p>
		</form>

	</div> <!-- end #login -->
</div> <!-- end .container -->
</body>
</html>