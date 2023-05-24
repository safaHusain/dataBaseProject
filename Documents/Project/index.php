
<?php

include 'header.php';
//echo 'home page';
?>

<style>
    /* Styles for the articles */
    .article {
        margin-bottom: 20px;
    }

    .article-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 5px;
        margin-top: 10px;
    }

    .article-meta {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }

    .article-description {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .read-more-link {
        display: inline-block;
        font-size: 14px;
        color: #333;
        text-decoration: none;
    }

    .read-more-link:hover {
        text-decoration: underline;
        color:#ba001f;
    }

    .article-divider {
        margin-top: 20px;
        border: none;
        border-top: 1px solid #ccc;
    }

    #main{
        width:100%;
        clear:both;
        padding-bottom:30px;
        padding-top:30px;
        margin-left: 30px;
    }
</style>

<?php

$db = new Connection();
$connection = $db->getConnection();

if (isset($_GET['pageno'])) {
    $start = $_GET['pageno'];
} else {
    $start = 0;
}

$end = 10;

$table = 'projectArticles';

// Retrieve articles from the database in reverse chronological order
$query = "SELECT * FROM projectArticles where status = 1 ORDER BY publishDate DESC";
//$start *= $end;
if (isset($start)) {
    $query .= ' limit ' . $start . ',' . $end;
}
//echo $query;
$result = mysqli_query($connection, $query);

// Add a horizontal line between articles and the header
//echo "<hr class='article-divider'>";
echo "<div id='main'>";

// Display each article
while ($row = mysqli_fetch_assoc($result)) {
    $articleId = $row['articleID'];
    $title = $row['title'];
    $category = $row['category'];
    $body = $row['text'];
    $publishedBy = $row['publishedBy'];
    $publishDate = $row['publishDate'];

    // Display article headline with a short description
    echo "<div class='article'>";
    echo "<h2 class='article-title'>$title</h2>";
    echo "<p class='article-meta'>$category - Published by $publishedBy on $publishDate</p>";
    echo "<p class='article-description'>" . substr($body, 0, 100) . "...</p>"; // Display only first 100 characters of the body
    // Link to the 'News Page' for the full version
    echo "<a class='read-more-link' href='news_page.php?article_id=$articleId'>Read More...</a>";

    echo "</div>"; // Close the article container
    echo "<hr class='article-divider'>"; // Add a horizontal line between articles
}
//pagination
echo '<table align="center" cellspacing = "2" cellpadding = "4" width="75%"><tr><td>';
    $pagination = new Pagination();
    $pagination->totalRecords($table);
    $pagination->setLimit($end);
    $pagination->page("", "");
    echo $pagination->firstBack();
    echo $pagination->where();
    echo $pagination->nextLast();
    echo '</td></tr></table>';
// Close the main container
echo "</div>";
// Close the database connection
mysqli_close($connection);
?>
