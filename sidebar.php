<!-- add latest posts -->
<a href="admin/login.php"><i class="fas fa-user fa-sm"></i> User Login</a>
<hr>

<h1>Recent Posts</h1>
<hr>
<ul class="list-unstyled">
    <!-- loop through blog_posts table, list up to 5 posts -->
    <?php
        $stmt = $db->query('SELECT postTitle, postSlug FROM blog_posts ORDER BY postID DESC LIMIT 5');
        while($row = $stmt->fetch()){
            echo '<li><a href="'.$row['postSlug'].'">'.$row['postTitle'].'</a></li>';
        }
    ?>
</ul>

<!-- add post categories -->
<h1>Categories</h1>
<hr>
<ul class="list-unstyled">
    <!-- loop through blog_cats, list contents -->
    <?php
    $stmt = $db->query('SELECT catTitle, catSlug FROM blog_cats ORDER BY catID DESC');
    while($row = $stmt->fetch()){
        echo '<li><a href="c-'.$row['catSlug'].'">'.$row['catTitle'].'</a></li>';
    }
    ?>
</ul>

<!-- add post tags -->
<h1>Tags</h1>
<hr>

<div class="tag">
    <?php
    $tagsArray = [];
    $stmt = $db->query('select distinct LOWER(postTags) as postTags from blog_posts where postTags != "" group by postTags');
    while($row = $stmt->fetch()){
        $parts = explode(',', $row['postTags']);
        foreach ($parts as $tag) {
            $tagsArray[] = $tag;
        }
    }

    $finalTags = array_unique($tagsArray);
    foreach ($finalTags as $tag) {
        echo "<a href='t-".$tag."' class='tag-cloud'>".ucwords($tag)."</a>";
    }
    ?>
</div>