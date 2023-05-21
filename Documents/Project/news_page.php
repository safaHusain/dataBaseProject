
<?php

include 'header.php';

$db = new Connection();
$connection = $db->getConnection();

// Retrieve the article ID from the URL parameter
if (isset($_GET['article_id'])) {
    $articleId = $_GET['article_id'];

    // Validate and sanitize the input (e.g., using mysqli_real_escape_string)
    $articleId = mysqli_real_escape_string($connection, $articleId);

    // Query the database to retrieve the full article based on the article ID
    $query = "SELECT * FROM projectArticles WHERE articleID = $articleId";
    $result = mysqli_query($connection, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $title = $row['title'];
        $category = $row['category'];
        $body = $row['text'];
        $publishedBy = $row['publishedBy'];
        $publishDate = $row['publishDate'];
        $likes = $row['likes'];

        // Display the full article
        echo "<div class='article'>";
        echo "<h2 class='articleNews-title'>$title</h2>";
        echo "<p class='articleNews-meta'>$category - Published by $publishedBy on $publishDate</p>";
        echo "<p class='articleNews-body'>$body</p>";
        echo "</div>";

        // Display the thumbs-up button and count
        echo "<div class='like-button'>";
        echo "<div class='like-count'>Likes: <span id='like-count'>$likes</span></i>";
        echo " <i class='fa-solid fa-thumbs-up' id='like-btn' data-article-id='$articleId'>&#128077;</button>";
        echo "</div>";

       
        // <i class="fa-thin fa-thumbs-up"></i>

        // Display the comment section
        echo "<div class='comment-section'>";
        echo "<h3>Comments</h3>";

        // Display the comment form
        echo "<form class='comment-form' id='comment-form' method='POST'>";
        echo "<input type='hidden' name='article_id' value='$articleId'>";
        echo "<input type='text' name='author' placeholder='Your Name' required>";
        echo "<textarea name='comment' placeholder='Your Comment' required></textarea>";
        echo "<button type='submit'>Submit Comment</button>";
        echo "</form>";

        // Fetch and display the comments for the article
        $query = "SELECT * FROM comments WHERE article_id = $articleId ORDER BY created_at DESC";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<ul class='comment-list'>";
            while ($comment = mysqli_fetch_assoc($result)) {
                $commentId = $comment['id'];
                $commentAuthor = $comment['author'];
                $commentContent = $comment['comment'];
                $commentTimestamp = $comment['created_at'];

                echo "<li class='comment-item'>";
                echo "<div class='author'>$commentAuthor</div>";
                echo "<div class='timestamp'>$commentTimestamp</div>";
                echo "<div class='content'>$commentContent</div>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "No comments found.";
        }

        echo "</div>";
    } else {
        echo "Article not found.";
    }
} else {
    echo "Invalid article ID.";
}

// Close the database connection
mysqli_close($connection);

include 'footer.php';
?>
