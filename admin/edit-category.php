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
	<title>Admin - Edit Category | Blog de Omnibus</title>

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
	<p><a href="categories.php">← Back to Categories Index</a></p>
	<hr>

	<h2 class="text-dark">Edit Category</h2>

	<?php
	// process submitted form
	if(isset($_POST['submit'])){
		// collect form data
		extract($_POST);
		// basic form validation
		if($catID ==''){
			$error[] = 'This post is missing a valid id!.';
		}
		if($catTitle ==''){
			$error[] = 'Please enter the title.';
		}
		if(!isset($error)){
			try {
				$catSlug = slug($catTitle);
				// insert into database
				$stmt = $db->prepare('UPDATE blog_cats SET catTitle = :catTitle, catSlug = :catSlug WHERE catID = :catID') ;
				$stmt->execute(array(
					':catTitle' => $catTitle,
					':catSlug' => $catSlug,
					':catID' => $catID
				));
				// redirect to index page
				header('Location: categories.php?action=updated');
				exit;
			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
		}
	}
	// check for errors, display errors
	if(isset($error)){
		foreach($error as $error){
			echo $error.'<br />';
		}
	}
		try {
			$stmt = $db->prepare('SELECT catID, catTitle FROM blog_cats WHERE catID = :catID') ;
			$stmt->execute(array(':catID' => $_GET['id']));
			$row = $stmt->fetch(); 
		} catch(PDOException $e) {
		    echo $e->getMessage();
		}
	?>

	<!-- EDIT CATEGORY -->
	<form action='' method='post'>
		<input type='hidden' name='catID' value='<?php echo $row['catID'];?>'>
		<!-- category -->
		<p><label>Edit Category Name</label><br />
		<input type='text' name='catTitle' value='<?php echo $row['catTitle'];?>' class="form-control"></p>
		<!-- submit -->
		<p><input type='submit' name='submit' value='Update Category' class="btn btn-dark add-btn"></p>
	</form>

</div> <!-- end .container -->

<!-- FOOTER -->
<?php include('../includes/footer.php');?>