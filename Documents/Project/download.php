<?php
if (isset($_GET['file'])) {
    $filePath = $_GET['file'];

    if (file_exists($filePath)) {
        // Set the appropriate headers for the file download
        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Disposition: attachment; filename=\"" . basename($filePath) . "\"");

        // Read the file and output it to the user
        readfile($filePath);
    } else {
        echo "File not found.";
    }
} else {
    echo "Invalid file path.";
}
?>
