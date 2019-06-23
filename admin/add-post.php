<?php //include config
require_once('../includes/config.php');
//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Admin - Add Post | Blog de Omnibus</title>

	<!-- CSS -->
	<link rel="stylesheet" href="../normalize.css">
	<link rel="stylesheet" href="../main.css">

	<!-- FONTS -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,700|Martel&display=swap" rel="stylesheet">

	<!-- SCRIPT -->
	<!-- Tiny MCE Text Editor CDN -->
	<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
	<script>
			tinymce.init({
				selector: "textarea",
				plugins: [
					"advlist autolink lists link image charmap print preview anchor",
					"searchreplace visualblocks code fullscreen",
					"insertdatetime media table contextmenu paste"
				],
				toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
			});
	</script>
</head>
<body>

<div class="container">
	<!-- ADMIN MENU -->
	<?php include('menu.php');?>
	<hr>
	<p><a href="./">← Back to Blog Post Index</a></p>
	<hr>

	<h2 class="text-dark">Add Post</h2>

	<?php
	// process submitted form
	if(isset($_POST['submit'])){
		// collect form data
		extract($_POST);
		// basic form validation
		if($postTitle ==''){
			$error[] = 'Please enter the title.';
		}
		if($postDesc ==''){
			$error[] = 'Please enter the description.';
		}
		if($postCont ==''){
			$error[] = 'Please enter the content.';
		}
		if(!isset($error)){
			try {
				$postSlug = slug($postTitle);
				// insert into database
				$stmt = $db->prepare('INSERT INTO blog_posts (postTitle,postSlug,postDesc,postCont,postDate,postTags) VALUES (:postTitle, :postSlug, :postDesc, :postCont, :postDate, :postTags)') ;
				$stmt->execute(array(
					':postTitle' => $postTitle,
					':postSlug' => $postSlug,
					':postDesc' => $postDesc,
					':postCont' => $postCont,
					':postDate' => date('Y-m-d H:i:s'),
					':postTags' => $postTags
				));
				$postID = $db->lastInsertId();
				// add categories
				if(is_array($catID)){
					foreach($_POST['catID'] as $catID){
						$stmt = $db->prepare('INSERT INTO blog_cat_cats (postID,catID)VALUES(:postID,:catID)');
						$stmt->execute(array(
							':postID' => $postID,
							':catID' => $catID
						));
					}
				}
				// redirect to index page
				header('Location: index.php?action=added');
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

	<!-- ADD POST -->
	<form action='' method='post'>
		<!-- Title -->
		<p><label>Title</label><br/>
		<input type='text' name='postTitle' value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>' class="form-control"></p>
		<!-- Description -->
		<p><label>Description</label><br/>
		<textarea name='postDesc' cols='60' rows='10' class="form-control"><?php if(isset($error)){ echo $_POST['postDesc'];}?></textarea></p>
		<!-- Content -->
		<p><label>Content</label><br/>
		<textarea name='postCont' cols='60' rows='10' class="form-control"><?php if(isset($error)){ echo $_POST['postCont'];}?></textarea></p>
		<!-- Tags -->
		<p><label>Tags (comma separated)</label><br/>
			<input type='text' name='postTags' value='<?php if(isset($error)){ echo $_POST['postTags'];}?>' class="form-control">
		</p>
		<!-- Categories -->
		<fieldset class="form-check">
			<legend>Categories</legend>
			<?php	
			$stmt2 = $db->query('SELECT catID, catTitle FROM blog_cats ORDER BY catTitle');
			while($row2 = $stmt2->fetch()){
				if(isset($_POST['catID'])){
					if(in_array($row2['catID'], $_POST['catID'])){
                       $checked="checked='checked'";
                    }else{
                       $checked = null;
                    }
				}
			    echo "<input type='checkbox' class='form-check-input' name='catID[]' value='".$row2['catID']."' $checked> ".$row2['catTitle']."<br />";
			}
			?>
		</fieldset>

		<p><input type='submit' name='submit' value='Submit Post' class='btn btn-dark add-btn'></p>
	</form>

</div> <!-- end .container -->