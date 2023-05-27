<head>
    <!-- Your head code here -->
</head>
<body>
    <?php
    // Your PHP code here
    ?>

    <div class="bigdiv">
        <!-- Your HTML code here -->

        <div class="like-button">
            <h3 class="likeTit">likes</h3>
            <form method="post" id="likeForm" action="">
                <button name="likeButton" type="submit" id="likeButton" value="like"><i class="fa-solid fa-thumbs-up" id="like-btn" data-article-id="<?php echo $articleId; ?>">&#128077;</i></button>
            </form>
            <div class="like-count" id="likebuttonid"><span id="like-count">Likes: <?php echo $theliked; ?></span></div>
        </div>

        <!-- Rest of your HTML code -->
    </div>

    <script>
        // Add an event listener to the thumbs-up button
        var likeButton = document.getElementById("likeButton");
        likeButton.addEventListener("click", updateLikesCount);

        function updateLikesCount(event) {
            event.preventDefault();

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET", "updateLikes.php", true);
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var likesContainer = document.getElementById("likebuttonid");
                    likesContainer.innerHTML = xmlhttp.responseText;
                }
            };
            xmlhttp.send();
        }
    </script>
</body>

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

        //Display the full article
        echo "<div class='article'>";
        echo "<h2 class='articleNews-title'>$title</h2>";
        echo "<p class='articleNews-meta'>$category - Published by $publishedBy on $publishDate</p>";
        echo "<p class='articleNews-body'>$body</p>";
        echo "</div>";
        echo "</div>";

        // Query the projectMedia table based on the article ID to fetch the associated media files
        // Use a prepared statement to prevent SQL injection
        $mediaQuery = "SELECT * FROM projectMedia WHERE article_id = ?";
        $mediaStmt = mysqli_prepare($connection, $mediaQuery);
        mysqli_stmt_bind_param($mediaStmt, 'i', $articleId);
        mysqli_stmt_execute($mediaStmt);
        $mediaResult = mysqli_stmt_get_result($mediaStmt);

        if (mysqli_num_rows($mediaResult) > 0) {
            echo "<h3 class='mediaFiles'>Media Files</h3>";
            echo "<div class='media-section'>";

            // Display each media file
            while ($mediaRow = mysqli_fetch_assoc($mediaResult)) {
                $mediaName = $mediaRow['name'];
                $mediaType = $mediaRow['type'];

                // Display the media file based on its type
                echo "<div class='media-file'>";
                echo "<h4>$mediaName</h4>";

                if (strpos($mediaType, 'image') !== false) {
                    // Display an image file
                    echo "<div class='divImg'><img src='uploads/$mediaName' alt='$mediaName' class='media-image'></div>";
                } elseif (strpos($mediaType, 'video') !== false) {
                    // Display a video file
                    echo "<div class='divVid'><video controls src='uploads/$mediaName' class='media-video'></video></div>";
                } elseif (strpos($mediaType, 'audio') !== false) {
                    // Display an audio file
                    echo "<div class='divAud'><audio controls src='uploads/$mediaName' class='media-audio'></audio></div>";
                }

                echo "</div>";
            }

            echo "</div>";
        } else {
            echo "<p>No media files found for the specified article.</p>";
        }

        // Query the projectDownloads table based on the article ID to fetch the downloadable files
        // Use a prepared statement to prevent SQL injection
        $downloadsQuery = "SELECT * FROM projectDownloads WHERE article_id = ?";
        $downloadsStmt = mysqli_prepare($connection, $downloadsQuery);
        mysqli_stmt_bind_param($downloadsStmt, 'i', $articleId);
        mysqli_stmt_execute($downloadsStmt);
        $downloadsResult = mysqli_stmt_get_result($downloadsStmt);

        if (mysqli_num_rows($downloadsResult) > 0) {
            echo "<h3 class='downloadFiles'>Downloadable Files</h3>";
            echo "<div class='download-section'>";

            // Display each downloadable file
            while ($downloadRow = mysqli_fetch_assoc($downloadsResult)) {
                $filename = $downloadRow['name'];
                $filepath = 'uploads/' . $filename;

                // Display the download link
                echo "<div class='download-file'>";
                echo "<a href='download.php?file=$filename' class='download-link'>Click Here</a>";
                echo "</div>";
            }

            echo "</div>";
        } else {
            echo "<p>No downloadable files found for the specified article.</p>";
        } // Display the thumbs-up button and count
        // Check if the user has already liked the article
        $sessionid = $_SESSION['uid'];
        $query = "SELECT COUNT(*) AS liked FROM ProjectLikes WHERE artical_id = $articleId AND user_id = $sessionid";
        $allLikeQry = "SELECT COUNT(*) AS liked FROM ProjectLikes WHERE artical_id = $articleId";
        $result = mysqli_query($connection, $query);
        $theresult = mysqli_query($connection, $allLikeQry);
        $row = mysqli_fetch_assoc($result);
        $therow = mysqli_fetch_assoc($theresult);

        $theliked = $therow['liked'];
        echo '<div class="bigdiv">';
        echo "<div class='like-button'>";
        echo "<h3 class='likeTit'>likes</h3>";
        echo "<form method='post' id ='likeForm' action=''>
    <button name='likeButton' type='submit' id='likeButton' value='like'><i class='fa-solid fa-thumbs-up' id='like-btn' data-article-id='$articleId'>&#128077;</i></button>
</form>";

        echo "<div class='like-count' id ='likebuttonid'><span id='like-count'>Likes: $theliked</span></div>";
        $liked = $row['liked'];

        // Check if the like button is pressed
        if (isset($_POST['likeButton'])) {
            $userId = $_SESSION['uid'];

            // Check if the user has already liked the article
            $query = "SELECT * FROM ProjectLikes WHERE artical_id = $articleId AND user_id = $userId";
            $result = mysqli_query($connection, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                // User has already liked, so unlike the article
                $deleteQuery = "DELETE FROM ProjectLikes WHERE artical_id = $articleId AND user_id = $userId";
                $deleteResult = mysqli_query($connection, $deleteQuery);

                if ($deleteResult) {
                    // Refresh the page to reflect the updated like count
                    // header("Refresh:0");
                } else {
                    echo "<p class='error'>Error unliking the article: " . mysqli_error($connection) . "</p>";
                }
            } else {
                if ($userId == 69) {
                    // Assign user ID 69 as liked for unregistered users
                    $insertQuery = "INSERT INTO ProjectLikes (user_id, artical_id) VALUES (?, ?)";
                    $stmt = mysqli_prepare($connection, $insertQuery);

                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, 'ii', $userId, $articleId);
                        $insertResult = mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);

                        if ($insertResult) {
                            // Refresh the page to reflect the updated like count
                            //header("Refresh:0");
                        } else {
                            echo "<p class='error'>Error liking the article: " . mysqli_error($connection) . "</p>";
                        }
                    } else {
                        echo "<p class='error'>Error preparing the like statement: " . mysqli_error($connection) . "</p>";
                    }
                } else {
                    // Normal user like operation
                    $insertQuery = "INSERT INTO ProjectLikes (user_id, artical_id) VALUES (?, ?)";
                    $stmt = mysqli_prepare($connection, $insertQuery);

                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, 'ii', $userId, $articleId);
                        $insertResult = mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);

                        if ($insertResult) {
                            // Refresh the page to reflect the updated like count
                            //header("Refresh:0");
                        } else {
                            echo "<p class='error'>Error liking the article: " . mysqli_error($connection) . "</p>";
                        }
                    } else {
                        echo "<p class='error'>Error preparing the like statement: " . mysqli_error($connection) . "</p>";
                    }
                }
            }
        }

        echo "</div>";
        echo "</div>";


        echo "<br>";

        // Display the comment section
        echo "<div class='comment-section'>";
        echo "<h3 calss ='comTit' >Comments</h3>";

        // Display the comment form

        echo "<form class='comment-form' id='comment-form' method='POST'>";
        echo "<input type='hidden' name='article_id' value='$articleId'>";
        echo '<input type="text" id="usernameField" name="username" value="' . (isset($_SESSION['username']) ? $_SESSION['username'] : '') . '" disabled>';
        echo "<textarea id ='txtComment' name='comment' placeholder='Your Comment' required></textarea>";
        echo "<button type='submit' class='submit-comment' name='submit-comment'>Submit Comment</button>";
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

                // Refresh the page to show the new comment
                // header("Location: article.php?article_id=$articleId");
                // exit();
            } else {
                echo "<p class='error'>Error preparing the insert statement: " . mysqli_error($connection) . "</p>";
            }
        }

        // Display the existing comments for the article
        $commentQuery = "SELECT * FROM ProjectComments WHERE article_id = $articleId ORDER BY created_at DESC";
        $commentResult = mysqli_query($connection, $commentQuery);

        if (mysqli_num_rows($commentResult) > 0) {
            echo "<ul class='comment-list'>";

            while ($commentRow = mysqli_fetch_assoc($commentResult)) {
                $commentAuthor = $commentRow['author'];
                $commentContent = $commentRow['comment'];
                $commentCreatedAt = $commentRow['created_at'];

                echo "<li class='comment'>";
                echo "<p class='comment-meta'>Comment by $commentAuthor on $commentCreatedAt</p>";
                echo "<p class='comment-content'>$commentContent</p>";
                echo "</li>";
            }

            echo "</ul>";
        } else {
            echo "<p>No comments found for this article.</p>";
        }

        echo "</div>";

        mysqli_free_result($result);
        mysqli_free_result($mediaResult);
        mysqli_free_result($downloadsResult);
        mysqli_free_result($commentResult);
    } else {
        echo "<p>No article found with the specified ID.</p>";
    }

    mysqli_close($connection);
} else {
    echo "<p>No article ID specified.</p>";
}
echo '</div>';
include 'footer.php';
