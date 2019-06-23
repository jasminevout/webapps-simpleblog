<!-- HEAD/HEADER -->
<?php
	$pageTitle = "Tags";
	include('includes/header.php');
?>
	<div class="container">
		<p class="col posts-in"><i class="fas fa-tag fa-xs"></i> Posts tagged as <?php echo htmlspecialchars($_GET['id']);?></p>
		<hr>

		<div class="row">
			<!-- MAIN CONTENT -->
			<div id='main' class="col">
				<?php	
				try {
					$stmt = $db->prepare('SELECT postID, postTitle, postSlug, postDesc, postDate, postTags FROM blog_posts WHERE postTags like :postTags ORDER BY postID DESC');
					$stmt->execute(array(':postTags' => '%'.$_GET['id'].'%'));
					while($row = $stmt->fetch()){
						echo '<div>';
							// post title, link
							echo "<h1 class='post-title'>" . '<a href="'.$row['postSlug'].'">'.$row['postTitle'].'</a></h1>';
							// post meta - date, time, category
							echo "<p class='post-meta'>" . 'Posted on '.date('jS M Y H:i:s', strtotime($row['postDate'])).' in ';

								$stmt2 = $db->prepare('SELECT catTitle, catSlug FROM blog_cats, blog_cat_cats WHERE blog_cats.catID = blog_cat_cats.catID AND blog_cat_cats.postID = :postID');
								$stmt2->execute(array(':postID' => $row['postID']));

								$catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

								$links = array();
								foreach ($catRow as $cat)
								{
									$links[] = "<a href='c-".$cat['catSlug']."'>".$cat['catTitle']."</a>";
								}
								echo implode(", ", $links);

							echo '</p>';
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
							// post description
							echo '<p>'.$row['postDesc'].'</p>';	
							// read more link			
							echo "<p class='readmore'>" . "<a href='".$row['postSlug']."'>" . "Read More →</a></p>";				
						echo '</div>';

					}

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

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>