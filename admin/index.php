<?php
//include config
require_once('../includes/config.php');
//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
//show message from add / edit page
if(isset($_GET['delpost'])){ 
	$stmt = $db->prepare('DELETE FROM blog_posts WHERE postID = :postID') ;
	$stmt->execute(array(':postID' => $_GET['delpost']));
	//delete post categories. 
	$stmt = $db->prepare('DELETE FROM blog_cat_cats WHERE postID = :postID');
	$stmt->execute(array(':postID' => $_GET['delpost']));
	header('Location: index.php?action=deleted');
	exit;
} 
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Admin - Index | Blog de Omnibus</title>

	<!-- CSS -->
	<link rel="stylesheet" href="../normalize.css">
	<link rel="stylesheet" href="../main.css">

	<!-- FONTS -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,700|Martel&display=swap" rel="stylesheet">

	<!-- SCRIPT -->
	<script language="JavaScript" type="text/javascript">
		// Post delete confirmation
		function delpost(id, title)
		{
			if (confirm("Are you sure you want to delete '" + title + "'?"))
			{
			window.location.href = 'index.php?delpost=' + id;
			}
		}
	</script>
</head>
<body>

<div class="container">
	<!-- ADMIN MENU -->
	<?php include('menu.php');?>

	<!-- show message from add / edit page -->
	<?php 
	if(isset($_GET['action'])){ 
		echo '<h3>Post '.$_GET['action'].'.</h3>'; 
	} 
	?>

	<div class="row">
		<!-- display table of posts -->
		<table class="table">
			<tr>
				<th>Title</th>
				<th>Date</th>
				<th>Action</th>
			</tr>
			<?php
				try {
					$stmt = $db->query('SELECT postID, postTitle, postDate FROM blog_posts ORDER BY postID DESC');
					while($row = $stmt->fetch()){
						
						echo '<tr>';
						// post title
						echo '<td>'.$row['postTitle'].'</td>';
						// post date, time
						echo '<td>'.date('jS M Y', strtotime($row['postDate'])).'</td>';
						?>

						<!-- edit or delete post -->
						<td>
							<a href="edit-post.php?id=<?php echo $row['postID'];?>">Edit</a> | 
							<a href="javascript:delpost('<?php echo $row['postID'];?>','<?php echo $row['postTitle'];?>')">Delete</a>
						</td>
						
						<?php 
						echo '</tr>';
					}
				} catch(PDOException $e) {
					echo $e->getMessage();
				}
			?>
		</table>
	</div> <!-- end .row -->

	<!-- add post button -->
	<div class="row">
		<div class="btn btn-dark add-btn">
			<a href='add-post.php'>Add Post <i class="fas fa-plus fa-xs"></i></a>
		</div>
	</div> <!-- end .row -->
</div> <!-- end .container -->

<!-- FOOTER -->
<?php include('../includes/footer.php');?>