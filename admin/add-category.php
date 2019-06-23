<?php
// include config
require_once('../includes/config.php');
// if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Admin - Add Category | Blog de Omnibus</title>

	<!-- CSS -->
	<link rel="stylesheet" href="../normalize.css">
	<link rel="stylesheet" href="../main.css">

	<!-- FONTS -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,700|Martel&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
	<!-- ADMIN MENU -->
	<?php include('menu.php');?>
	<hr>
	<p><a href="./">← Back to Categories Index</a></p>
	<hr>

	<h2 class="text-dark">Add Category</h2>

	<?php
	// process submitted form
	if(isset($_POST['submit'])){
		// collect form data
		extract($_POST);
		// basic form validation
		if($catTitle ==''){
			$error[] = 'Please enter the Category.';
		}
		if(!isset($error)){
			try {
				$catSlug = slug($catTitle);
				// insert into database
				$stmt = $db->prepare('INSERT INTO blog_cats (catTitle,catSlug) VALUES (:catTitle, :catSlug)') ;
				$stmt->execute(array(
					':catTitle' => $catTitle,
					':catSlug' => $catSlug
				));
				// redirect to index page
				header('Location: categories.php?action=added');
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

	<!-- ADD CATEGORY -->
	<form action='' method='post'>
		<!-- Category -->
		<p><label>Category Name</label><br />
		<input type='text' name='catTitle' value='<?php if(isset($error)){ echo $_POST['catTitle'];}?>' class="form-control"></p>
		<!-- Submit -->
		<p><input type='submit' name='submit' value='Add Category' class="btn btn-dark add-btn"></p>
	</form>

</div> <!-- end .container -->