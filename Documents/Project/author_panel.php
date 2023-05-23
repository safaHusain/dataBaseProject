<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

include 'header.php';

//echo 'author panel';
?>

<style>
    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"],
    textarea,
    select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
        resize: vertical;
    }

    input[type="file"] {
        margin-top: 5px;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 3px;
        cursor: pointer;
        margin-top: 10px;
        width: auto;
        align-self: flex-start;
    }

    .article-table {
        width: 100%;
        border-collapse: collapse;
    }

    .article-table th,
    .article-table td {
        padding: 10px;
        border: 1px solid #ccc;
    }

    .article-table th {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    .edit-link,
    .delete-link {
        text-decoration: none;
        color: #333;
    }

    .edit-link:hover,
    .delete-link:hover {
        text-decoration: underline;
        color:#ba001f;
    }

    .error {
        color: red;
        font-weight: bold;
    }

    h2{
        color: #ba001f;
        margin-bottom: 10px;
        margin-top: 10px;
        font-weight: bold;
    }

    p{
        color: #ba001f;
        margin-bottom: 5px;
        margin-top: 5px;
        font-weight: bold;
    }


</style>



<?php
if (isset($_POST["saved"])) {

    $category = $_POST['category'];
    $title = $_POST['title'];
    $body = $_POST['body'];

    // Validate form data
    if (empty($category) || empty($title) || empty($body)) {
        $error = "Please fill in all fields.";
    } else {
        // Process media attachment (image)
        $image = $_FILES['image'];
        $media = $_FILES['media'];
        $downloadable = $_FILES['downloadable'];

        // Validate media attachment
        if (empty($image['name']) || empty($media['name'])) {
            $error = "Please upload at least 1 image and 1 audio/video file.";
        } else {
            // Create instances of the classes
            $article = new Articles();
            $mediaObj = new Media();

            // Save article data and get the article ID
            $article->setTitle($title);
            $article->setCategory($category);
            $article->setText($body);
            //$article->addArticle();
            $articleId = $article->addArticle();
            //$db = mysqli_connect('localhost', 'u201902301', 'u201902301', 'db201902301');
            //$articleId = mysqli_insert_id($db);
            // Save media attachment (image)
            $imageName = $image['name'];
            $imageType = $image['type'];
            $imageSize = $image['size'];
            //$imageData = file_get_contents($image['tmp_name']);
            //upload image to folder
            $imageTarget = "uploads/" . basename($image['name']);
            move_uploaded_file($image['tmp_name'], $imageTarget);

            $mediaObj->setArticleId($articleId);
            //$mediaObj->setData($imageData);
            $mediaObj->setName($imageName);
            $mediaObj->setSize($imageSize);
            $mediaObj->setType($imageType);
            $mediaObj->saveMedia();

            // Process the media attachments (audio/video)
            $mediaName = $media['name'];
            $mediaType = $media['type'];
            $mediaSize = $media['size'];
            //$mediaData = file_get_contents($media['tmp_name']);
            //upload media to folder
            $mediaTarget = "uploads/" . basename($media['name']);
            move_uploaded_file($media['tmp_name'], $mediaTarget);

            $mediaObj->setArticleId($articleId);
            //$mediaObj->setData($mediaData);
            $mediaObj->setName($mediaName);
            $mediaObj->setSize($mediaSize);
            $mediaObj->setType($mediaType);
            $mediaObj->saveMedia();

            // Process the downloadable file if provided
            if (!empty($downloadable['name'])) {
                $downloadableName = $downloadable['name'];
                $downloadableType = $downloadable['type'];
                $downloadableSize = $downloadable['size'];
                //$downloadableData = file_get_contents($downloadable['tmp_name']);
                //upload download to folder
                $downloadTarget = "uploads/" . basename($downloadable['name']);
                move_uploaded_file($downloadable['tmp_name'], $downloadTarget);

                $download = new Downloads();
                $download->setArticleId($articleId);
                //$download->setData($downloadableData);
                $download->setName($downloadableName);
                $download->setSize($downloadableSize);
                $download->setType($downloadableType);
                $download->saveDownload();
            }

            // Save the article
            $saved = "Article saved successfully!";
        }
    }
} elseif (isset($_POST["published"])) {

    $category = $_POST['category'];
    $title = $_POST['title'];
    $body = $_POST['body'];

    // Validate form data
    if (empty($category) || empty($title) || empty($body)) {
        $error = "Please fill in all fields.";
    } else {
        // Process media attachment (image)
        $image = $_FILES['image'];
        $media = $_FILES['media'];
        $downloadable = $_FILES['downloadable'];

        // Validate media attachment
        if (empty($image['name']) || empty($media['name'])) {
            $error = "Please upload at least 1 image and 1 audio/video file.";
        } else {
            // Create instances of the classes
            $article = new Articles();
            $mediaObj = new Media();

            // Save article data and get the article ID
            $article->setTitle($title);
            $article->setCategory($category);
            $article->setText($body);
            //$article->addArticle();
            $articleId = $article->publishArticle();
            //$db = mysqli_connect('localhost', 'u201902301', 'u201902301', 'db201902301');
            //$articleId = mysqli_insert_id($db);
            // Save media attachment (image)
            $imageName = $image['name'];
            $imageType = $image['type'];
            $imageSize = $image['size'];
            //$imageData = file_get_contents($image['tmp_name']);
            //upload image to folder
            $imageTarget = "uploads/" . basename($image['name']);
            move_uploaded_file($image['tmp_name'], $imageTarget);

            $mediaObj->setArticleId($articleId);
            //$mediaObj->setData($imageData);
            $mediaObj->setName($imageName);
            $mediaObj->setSize($imageSize);
            $mediaObj->setType($imageType);
            $mediaObj->saveMedia();

            // Process the media attachments (audio/video)
            $mediaName = $media['name'];
            $mediaType = $media['type'];
            $mediaSize = $media['size'];
            //$mediaData = file_get_contents($media['tmp_name']);
            //upload media to folder
            $mediaTarget = "uploads/" . basename($media['name']);
            move_uploaded_file($media['tmp_name'], $mediaTarget);

            $mediaObj->setArticleId($articleId);
            //$mediaObj->setData($mediaData);
            $mediaObj->setName($mediaName);
            $mediaObj->setSize($mediaSize);
            $mediaObj->setType($mediaType);
            $mediaObj->saveMedia();

            // Process the downloadable file if provided
            if (!empty($downloadable['name'])) {
                $downloadableName = $downloadable['name'];
                $downloadableType = $downloadable['type'];
                $downloadableSize = $downloadable['size'];
                //$downloadableData = file_get_contents($downloadable['tmp_name']);
                //upload download to folder
                $downloadTarget = "uploads/" . basename($downloadable['name']);
                move_uploaded_file($downloadable['tmp_name'], $downloadTarget);

                $download = new Downloads();
                $download->setArticleId($articleId);
                //$download->setData($downloadableData);
                $download->setName($downloadableName);
                $download->setSize($downloadableSize);
                $download->setType($downloadableType);
                $download->saveDownload();
            }

            // Publish the article
            $saved = "Article published successfully!";
        }
    }
}
?>


<div id="main">
    <div class="container">
        <h2>Create News Article</h2>

<?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<?php if (isset($saved)): ?>
            <p style="color: green;"><?php echo $saved; ?></p>
<?php endif; ?>



        <form method="POST" enctype="multipart/form-data">
            <label for="category">Category:</label>
            <select id="category" name="category">
                <option value="politics">Politics</option>
                <option value="business">Business</option>
                <option value="sports">Sports</option>
                <option value="art">Art</option>
            </select>


            <label for="title">Title:</label>
            <input type="text" id="title" name="title">

            <label for="body">Body:</label>
            <textarea id="body" name="body" rows="5"></textarea>

            <label for="images">Images:</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
            <input type="file" id="image" name="image" accept="image/*" multiple>

            <label for="media">Audio/Video:</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
            <input type="file" id="media" name="media" accept="audio/*,video/*">

            <label for="downloadable">Downloadable File (Optional):</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
            <input type="file" id="downloadable" name="downloadable">

            <input type="submit" name="saved" value="Save for later">
            <p>OR</p>
            <input type="submit" name="published" value="Publish">

        </form>
    </div>

</div>

<?php
// Retrieve articles from the database where status = 0
//$db = Database::getInstance();
//$query = "SELECT * FROM projectArticles WHERE status = 0";
//$result = mysqli_query($db, $query);

if (isset($_GET['pageno'])) {
    $start = $_GET['pageno'];
} else {
    $start = 0;
}

$end = 10;

$table = 'projectArticles';

$articles = new Articles();
$row = $articles->getAllUnpublishedArticlesForAuthor($start, $end);

echo '<h2>Unpublished articles</h2>';
// Check if there are any articles
if (!empty($row)) {
    // Start the HTML table
    echo '<table class="article-table">';
    echo '<tr><th>Article ID</th><th>Title</th><th>Category</th><th>Edit</th><th>Delete</th></tr>';

    // Loop through the articles and display them in the table
    for ($i = 0; $i < count($row); $i++) {
        echo '<tr>';
        echo '<td>' . $row[$i]->articleID . '</td>';
        echo '<td>' . $row[$i]->title . '</td>';
        echo '<td>' . $row[$i]->category . '</td>';

        // Edit column - link to the edit_article.php page with the article ID
        echo '<td><a class="edit-link" href="edit_article.php?article_id=' . $row[$i]->articleID . '">Edit</a></td>';

        // Delete column - link to the delete_article.php page with the article ID
        echo '<td><a class="delete-link" href="delete_article.php?article_id=' . $row[$i]->articleID . '">Delete</a></td>';

        echo '</tr>';
    }

    // End the HTML table
    echo '</table>';
    
    echo '<table align="center" cellspacing = "2" cellpadding = "4" width="75%"><tr><td>';
    $pagination = new Pagination();
    $pagination->totalRecords($table);
    $pagination->setLimit($end);
    $pagination->page("", "");
    echo $pagination->firstBack();
    echo $pagination->where();
    echo $pagination->nextLast();
    echo '</td></tr></table>';
    
} else {
    echo '<p class="error">' . $query . '</p>';
    echo '<p class="error"> You dont have any unpublished articles</p>';
    echo '<p class="error">' . mysqli_error($db) . '</p>';
}
?>
