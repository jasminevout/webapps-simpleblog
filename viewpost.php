<?php require('includes/config.php');
	//retrieve post from blog_posts 
	$stmt = $db->prepare('SELECT postID, postTitle, postCont, postDate, postTags FROM blog_posts WHERE postSlug = :postSlug');
	$stmt->execute(array(':postSlug' => $_GET['id']));
	$row = $stmt->fetch();

	//if post does not exist, redirect user to index
	if($row['postID'] == ''){
		header('Location: ./');
		exit;
	}
?>

<!-- HEAD/HEADER -->
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php echo $row['postTitle'] ?> | Blog de Omnibus</title>

	<!-- CSS -->
	<link rel="stylesheet" href="normalize.css">
	<link rel="stylesheet" href="main.css">

	<!-- FONTS -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,700|Martel&display=swap" rel="stylesheet">

</head>
<body>
	<!-- HEADER -->
	<div class="container">
		<div class="row">
			<div class="col">
				<h1 class="page-title">
					<a href="index.php">
						<i class="fas fa-book-reader"></i> Blog de Omnibus
					</a>
				</h1>
				<hr/>
			</div> <!-- end .col -->
		</div> <!-- end .row -->
	</div> <!-- end .container -->

	<div class="container">
		<p class="col posts-in"><a href="./">← Back to Blog Index</a></p>
		<hr>

		<div class="row">
			<!-- MAIN CONTENT -->
			<div id='main' class="col">
				<!-- -->
				<?php	
					//display post in full
					echo '<div>';
						//post title
						echo "<h1 class='post-title'>".$row['postTitle'].'</h1>';
						//post meta - time, date, category
						echo "<p class='post-meta'>" . 'Posted on '.date('jS M Y H:i:s', strtotime($row['postDate'])).' in ';
							$stmt2 = $db->prepare('SELECT catTitle, catSlug	FROM blog_cats, blog_cat_cats WHERE blog_cats.catID = blog_cat_cats.catID AND blog_cat_cats.postID = :postID');
							$stmt2->execute(array(':postID' => $row['postID']));

							$catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
							$links = array();
							foreach ($catRow as $cat){
								$links[] = "<a href='c-".$cat['catSlug']."'>".$cat['catTitle']."</a>";
							}
							echo implode(", ", $links);
						echo '</p>';
						//post content
						echo '<p>'.$row['postCont'].'</p>';
						//post meta - tags
						echo "<p class='post-meta'>Tagged as: ";
						$links = array();
						$parts = explode(',', $row['postTags']);
						foreach ($parts as $tag)
						{
							$links[] = "<a href='t-".$tag."'>".$tag."</a>";
						}
						echo implode(", ", $links);
						echo '</p>';	
														
					echo '</div>';
				?>
			</div> <!-- end #main -->

			<!-- SIDEBAR -->
			<div id='sidebar' class="col-md-3">
				<?php require('sidebar.php'); ?>
			</div> <!-- end #sidebar -->

			<div id='clear'></div>
		</div> <!-- end .row -->
	</div> <!-- end .container -->

</body>
</html>