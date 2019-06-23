<?php
// include config
require_once('../includes/config.php');
// if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
// show message from add / edit page
if(isset($_GET['delcat'])){ 
	$stmt = $db->prepare('DELETE FROM blog_cats WHERE catID = :catID') ;
	$stmt->execute(array(':catID' => $_GET['delcat']));
	header('Location: categories.php?action=deleted');
	exit;
} 
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Admin - Categories | Blog de Omnibus</title>

	<!-- CSS -->
	<link rel="stylesheet" href="../normalize.css">
	<link rel="stylesheet" href="../main.css">

	<!-- FONTS -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,700|Martel&display=swap" rel="stylesheet">

	<!-- SCRIPT -->
	<script language="JavaScript" type="text/javascript">
	function delcat(id, title)
	{
		if (confirm("Are you sure you want to delete '" + title + "'"))
		{
			window.location.href = 'categories.php?delcat=' + id;
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
		echo '<h3>Category '.$_GET['action'].'.</h3>'; 
	} 
	?>

	<div class="row">
		<!-- display table of categories -->
		<table class="table">
		<tr>
			<th>Title</th>
			<th>Action</th>
		</tr>
		<?php
			try {
				$stmt = $db->query('SELECT catID, catTitle, catSlug FROM blog_cats ORDER BY catTitle DESC');
				while($row = $stmt->fetch()){
					
					echo '<tr>';
					// category title
					echo '<td>'.$row['catTitle'].'</td>';
					?>

					<!-- edit or delete post -->
					<td>
						<a href="edit-category.php?id=<?php echo $row['catID'];?>">Edit</a> | 
						<a href="javascript:delcat('<?php echo $row['catID'];?>','<?php echo $row['catSlug'];?>')">Delete</a>
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
			<a href='add-category.php'>Add Category <i class="fas fa-plus fa-xs"></i></a>
		</div>
	</div> <!-- end .row -->

</div> <!-- end .container -->

<!-- FOOTER -->
<?php include('../includes/footer.php');?>