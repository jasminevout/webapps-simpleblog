<!-- PAGE HEADER -->
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

<!-- MENU ITEMS -->
	<div class="row">
		<div class="col">
			<p class="loggedinas"><i class="fas fa-user fa-xs"></i> Logged in as <span class="loggedinas-user"><?=$_SESSION['username'];?></span></p>
		</div> <!-- end .col -->

		<ul id='adminmenu' class="list-inline col-md-6 col-lg-5 float-right">
			<li class="list-inline-item"><a href='index.php'>Blog</a></li>
			<li class="list-inline-item"><a href='categories.php'>Categories</a></li>
			<li class="list-inline-item"><a href='users.php'>Users</a></li>
			<li class="list-inline-item"><a href="../" target="_blank">View Website</a></li>
			<li class="list-inline-item"><a href='logout.php'>Logout</a></li>
		</ul>

		<div class='clear'></div>
		<hr />
	</div>
</div>