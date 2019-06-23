<!-- HEAD/HEADER -->
<?php
	$pageTitle = "Home";
	include('includes/header.php');
?>

	<div class="container margin-top">
		<div class="row">

			<!-- MAIN CONTENT -->
			<div id='main' class="col">
				<!-- list content from blog_posts -->
				<?php
					try {
						// pagination
						// instantiate class
						$pages = new Paginator('5','p');
						// retrieve records
						$stmt = $db->query('SELECT postID FROM blog_posts');
						// determine number of records
						$pages->set_total($stmt->rowCount());
						// update query
						$stmt = $db->query('SELECT postID, postTitle, postSlug, postDesc, postDate, postTags FROM blog_posts ORDER BY postID DESC '.$pages->get_limit());
						while($row = $stmt->fetch()){
								// post title, link
								echo "<h1 class='post-title'>" . '<a href="'.$row['postSlug'].'">'.$row['postTitle'].'</a></h1>';
								// post meta - date, time, category
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
								// post description
								echo '<p>'.$row['postDesc'].'</p>';	
								// read more link			
								echo "<p class='readmore'>" . "<a href='".$row['postSlug']."'>" . "Read More →</a></p>";
								// post meta - tags
								echo "<p class='post-meta'>" . 'Tagged as: ';
								$links = array();
								$parts = explode(',', $row['postTags']);
								foreach ($parts as $tag)
								{
									$links[] = "<a href='t-".$tag."'>".$tag."</a>";
								}
								echo implode(", ", $links);
								echo '</p>';							
						}
						// display pagination
						echo $pages->page_links();
					} catch(PDOException $e) {
						echo $e->getMessage();
					}
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