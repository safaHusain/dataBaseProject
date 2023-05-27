<?php
include 'Connection.php';

// Display the thumbs-up button and count
// Check if the user has already liked the article
$allLikeQry = "SELECT COUNT(*) AS liked FROM ProjectLikes WHERE artical_id = $articleId";
$theresult = mysqli_query($connection, $allLikeQry);
$therow = mysqli_fetch_assoc($theresult);

$theliked = $therow['liked'];
echo "<div class='like-count'><span id='like-count'>Likes: $theliked</span></div>";


?>