<?php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the comment and article ID from the form data
    $comment = $_POST['comment'];
    $articleId = $_POST['article_id'];

    // Perform any necessary validation and sanitization on the input

    // Save the comment in the database
    $db = new Connection();
    $connection = $db->getConnection();

    // Prepare the SQL statement
    $query = "INSERT INTO comments (articleID, commentText) VALUES (?, ?)";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, 'is', $articleId, $comment);

    // Execute the statement
    if (mysqli_stmt_execute($statement)) {
        // Comment saved successfully
        echo "success";
    } else {
        // Failed to save the comment
        echo "error";
    }

    // Close the statement and database connection
    mysqli_stmt_close($statement);
    mysqli_close($connection);
} else {
    // If the form is not submitted, redirect back to the article page or show an error message
    header("Location: article.php?article_id=$articleId");
    exit();
}
