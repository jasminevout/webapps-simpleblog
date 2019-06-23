<?php
//include config
require_once('../includes/config.php');
//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
//show message from add / edit page
if(isset($_GET['deluser'])){ 
	//if user id is 1 ignore
	if($_GET['deluser'] !='1'){
		$stmt = $db->prepare('DELETE FROM blog_members WHERE memberID = :memberID') ;
		$stmt->execute(array(':memberID' => $_GET['deluser']));
		header('Location: users.php?action=deleted');
		exit;
	}
} 
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Admin - Users | Blog de Omnibus</title>

	<!-- CSS -->
	<link rel="stylesheet" href="../normalize.css">
	<link rel="stylesheet" href="../main.css">

	<!-- FONTS -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,700|Martel&display=swap" rel="stylesheet">

	<!-- SCRIPT -->
	<script language="JavaScript" type="text/javascript">
	function deluser(id, title)
	{
		if (confirm("Are you sure you want to delete '" + title + "'?"))
		{
		window.location.href = 'users.php?deluser=' + id;
		}
	}
	</script>
</head>
<body>
	<div class="container">
		<?php include('menu.php');?>

		<?php 
			//show message from add / edit page
			if(isset($_GET['action'])){ 
				echo '<h3>User '.$_GET['action'].'.</h3>'; 
			} 
		?>

		<div class="row">
			<!-- display table of users -->
			<table class="table">
			<tr>
				<th>Username</th>
				<th>Email</th>
				<th>Action</th>
			</tr>
			<?php
				try {
					$stmt = $db->query('SELECT memberID, username, email FROM blog_members ORDER BY username');
					while($row = $stmt->fetch()){
						
						echo '<tr>';
						// username
						echo '<td>'.$row['username'].'</td>';
						// email
						echo '<td>'.$row['email'].'</td>';
						?>

						<!-- edit or delete post -->
						<td>
							<a href="edit-user.php?id=<?php echo $row['memberID'];?>">Edit</a> 
							<!-- display delete button if ID is not 1 -->
							<?php if($row['memberID'] != 1){?>
								| <a href="javascript:deluser('<?php echo $row['memberID'];?>','<?php echo $row['username'];?>')">Delete</a>
							<?php } ?>
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

		<!-- add user button -->
		<div class="row">
			<div class="btn btn-dark add-btn">
				<a href='add-user.php'>Add User <i class="fas fa-plus fa-xs"></i></a>
			</div>
		</div> <!-- end .row -->


	</div> <!-- end .container -->
<?php include('includes/footer.php');?>
