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
        echo "<button id='likeButton'><i class='fa-solid fa-thumbs-up' id='like-btn' data-article-id='$articleId'>&#128077;</button>";
        echo "</div>";

        // Display the comment section
        echo "<div class='comment-section'>";
        echo "<h3>Comments</h3>";

        // Display the comment form
        echo "<form class='comment-form' id='comment-form' method='POST'>";
        echo "<input type='hidden' name='article_id' value='$articleId'>";
        echo "<input type='text' name='author' placeholder='Your Name' required>";
        echo '<input type="text" id="usernameField" name="username" value="<?php echo isset($_SESSION['uid']) ? $_SESSION['uid'] : ''; ?>">';
        echo "<textarea name='comment' placeholder='Your Comment' required></textarea>";
        echo "<button type='submit' name='submit-comment'>Submit Comment</button>";
        echo "</form>";

        // Check if the form is submitted
        if (isset($_POST['submit-comment'])) {
            // Get the values from the form
            $author = mysqli_real_escape_string($connection, $_POST['author']);
            $comment = mysqli_real_escape_string($connection, $_POST['comment']);

            // Insert the comment into the database
            $insertQuery = "INSERT INTO comments (article_id, author, comment, created_at) VALUES ('$articleId', '$author', '$comment', NOW())";
            $insertResult = mysqli_query($connection, $insertQuery);

            if ($insertResult) {
                echo "<p class='success'>Comment added successfully.</p>";
            } else {
                echo "<p class='error'>Error adding comment.</p>";
            }
        }

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
