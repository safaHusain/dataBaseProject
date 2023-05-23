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

        // Display the full article
        echo "<div class='article'>";
        echo "<h2 class='articleNews-title'>$title</h2>";
        echo "<p class='articleNews-meta'>$category - Published by $publishedBy on $publishDate</p>";
        echo "<p class='articleNews-body'>$body</p>";
        echo "</div>";




        // Display the thumbs-up button and count
        // Check if the user has already liked the article
        $sessionid = $_SESSION['uid'];
        $query = "SELECT COUNT(*) AS liked FROM ProjectLikes WHERE artical_id = $articleId and user_id = $sessionid";
        $allLikeQry = "SELECT COUNT(*) AS liked FROM ProjectLikes WHERE artical_id = $articleId  ";
        $result = mysqli_query($connection, $query);
        $theresult = mysqli_query($connection, $allLikeQry);
        $row = mysqli_fetch_assoc($result);
        $therow = mysqli_fetch_assoc($theresult);

        $theliked = $therow['liked'];
        echo "<div class='like-button'>";
        echo "<div class='like-count'>Likes: <span id='like-count'>$theliked</span></i>";
        $liked = $row['liked'];
        echo "<form method='post'>
        <button name ='likeButton' type= 'submit' id='likeButton' value='like'><i class='fa-solid fa-thumbs-up' id='like-btn' data-article-id='$articleId'>&#128077;

        </button>
        </form>";
        // Check if the like button is pressed
        if (isset($_POST['likeButton'])) {

            if ($liked > 0) {
                $userId = $_SESSION['uid'];
                $likeID = "SELECT * FROM `ProjectLikes` WHERE artical_id = $articleId and user_id = $userId";
                // $linkStmt = mysqli_prepare($connection, $likeID);
                $theresult = mysqli_query($connection, $likeID);
                $likeIDres = mysqli_fetch_assoc($theresult);
                $like_id = $likeIDres['id'];
                // echo "Like ID: " . $like_id;
                $deleteQry = "DELETE FROM ProjectLikes WHERE id = ?";
                $deletePrep = mysqli_prepare($connection, $deleteQry);
                $therow = mysqli_fetch_assoc($theresult);

                if ($deletePrep) {
                    mysqli_stmt_bind_param($deletePrep, 'i', $like_id);
                    mysqli_stmt_execute($deletePrep);
                    // echo "Delete: " . $d;
                    mysqli_stmt_close($deletePrep);

                    // Refresh the page to reflect the updated like count
                    //header("Location: article.php?article_id=$articleId");
                    header("Refresh:0");
                    // echo "Error:" . $likeStmt;
                    // exit();
                } else {
                    echo "<p class='error'>Error preparing the like statement: " . mysqli_error($connection) . "</p>";
                }
            } else {
                // Insert a new like record in the database
                $insertQuery = "INSERT INTO ProjectLikes (user_id, artical_id) VALUES (?, ?)";
                $stmt = mysqli_prepare($connection, $insertQuery);

                if ($stmt) {
                    $userId = $_SESSION['uid']; // Assuming the user ID is 1, replace with your actual user ID
                    mysqli_stmt_bind_param($stmt, 'ii', $userId, $articleId);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);




                    // Refresh the page to reflect the updated like count
                    //header("Location: article.php?article_id=$articleId");
                    header("Refresh:0");
                    // exit();
                } else {
                    echo "<p class='error'>Error preparing the uid statement: " . mysqli_error($connection) . "</p>";
                }
            }
        }


        echo "</div>";
        echo"<br>";
        // Display the comment section
        echo "<div class='comment-section'>";
        echo "<h3>Comments</h3>";

        // Display the comment form
        echo "<form class='comment-form' id='comment-form' method='POST'>";
        echo "<input type='hidden' name='article_id' value='$articleId'>";
        echo '<input type="text" id="usernameField" name="username" value="' . (isset($_SESSION['username']) ? $_SESSION['username'] : '') . '" disabled>';
        echo "<textarea id ='txtComment' name='comment' placeholder='Your Comment' required></textarea>";
        echo "<button type='submit' name='submit-comment'>Submit Comment</button>";
        echo "</form>";

        // Check if the form is submitted
        if (isset($_POST['submit-comment'])) {
            // Get the values from the form
            $author = $_SESSION['username'];
            $comment = $_POST['comment'];

            // Prepare the query using prepared statements
            $insertQuery = "INSERT INTO ProjectComments (article_id, author, comment, created_at) VALUES (?, ?, ?, NOW())";
            $stmt = mysqli_prepare($connection, $insertQuery);

            if ($stmt) {
                // Bind the parameters
                mysqli_stmt_bind_param($stmt, 'iss', $articleId, $author, $comment);

                // Execute the statement
                $insertResult = mysqli_stmt_execute($stmt);

                if ($insertResult) {
                    echo "<p class='success'>Comment added successfully.</p>";
                } else {
                    echo "<p class='error'>Error adding comment: " . mysqli_stmt_error($stmt) . "</p>";
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            } else {
                echo "<p class='error'>Error preparing the x statement: " . mysqli_error($connection) . "</p>";
            }
        }

        // Fetch and display the comments for the article
        $query = "SELECT * FROM ProjectComments WHERE article_id = $articleId ORDER BY created_at DESC";
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
