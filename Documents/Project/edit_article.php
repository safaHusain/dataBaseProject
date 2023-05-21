<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

include 'header.php';

$article_id = 0;

if (isset($_GET['article_id'])) {
    $article_id = $_GET['article_id'];
} elseif (isset($_POST['article_id'])) {
    $article_id = $_POST['article_id'];
}

echo $article_id;
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

    #articles-table {
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Link styles */
        a {
            color: #337ab7;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
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
        // Create instances of the classes
        $article = new Articles();

        // Save article data and get the article ID
        $article->setArticleID($article_id);
        $article->setTitle($title);
        $article->setCategory($category);
        $article->setText($body);
        $articleId = $article->updateArticle();

        $saved = "Article saved successfully!";

        $downloadable = $_FILES['downloadable'];
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
            $download->setArticleId($article_id);
            //$download->setData($downloadableData);
            $download->setName($downloadableName);
            $download->setSize($downloadableSize);
            $download->setType($downloadableType);
            $download->updateDownloadable();
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
        // Create instances of the classes
        $article = new Articles();

        // Save article data and get the article ID
        $article->setArticleID($article_id);
        $article->setTitle($title);
        $article->setCategory($category);
        $article->setText($body);
        $articleId = $article->publishSavedArticle();

        $saved = "Article published successfully!";

        $downloadable = $_FILES['downloadable'];
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
            $download->setArticleId($article_id);
            //$download->setData($downloadableData);
            $download->setName($downloadableName);
            $download->setSize($downloadableSize);
            $download->setType($downloadableType);
            $download->updateDownloadable();
        }
    }
}
?>

<div id="main">
    <div class="container">
        <h2>Edit News Article</h2>

<?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if (isset($saved)): ?>
            <p style="color: green;"><?php echo $saved; ?></p>
        <?php endif; ?>

        <?php
        $article = new Articles();
        $article->initWithId($article_id);

        $download = new Downloads();
        $download->initWithId($article_id);
        ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="category">Category:</label>
            <select id="category" name="category">
                <option value="<?php echo $article->getCategory(); ?>"><?php echo $article->getCategory(); ?></option>
                <option value="politics">Politics</option>
                <option value="business">Business</option>
                <option value="sports">Sports</option>
                <option value="art">Art</option>
            </select>


            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo $article->getTitle(); ?>">

            <label for="body">Body:</label>
            <textarea id="body" name="body" rows="5"><?php echo $article->getText(); ?></textarea>

            <!--            <label for="images">Images:</label>
                        <input type="file" id="image" name="image" accept="image/*" multiple>
            
                        <label for="media">Audio/Video:</label>
                        <input type="file" id="media" name="media" accept="audio/*,video/*">-->

            <label for="downloadable">Downloadable File (Optional):</label>
            <p>Old file: <?php echo $download->getName(); ?> </p>
            <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
            <p>New File: </p><input type="file" id="downloadable" name="downloadable">


            <input type="submit" name="saved" value="Save for later">
            <p>OR</p>
            <input type="submit" name="published" value="Publish">

        </form>
    </div>

</div>