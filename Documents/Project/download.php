<?php
$filename = $_GET['file'];
$filepath = 'uploads/' . $filename;
echo $filename;
echo '<br>';
echo $filepath;
echo '<br>';
if (!empty($filename) && file_exists($filepath)) {
    // Define Headers
    if (!empty($filename) && file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filepath));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        ob_clean();
        flush();
        readfile($filepath);
        exit;
    }
} else {
    echo "File not found.";
}
