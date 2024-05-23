<?php
include("./Model.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_upload'])) {
    $file = $_FILES['file_upload'];
    $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);

    if ($fileType !== 'json') {
        echo "Only JSON files are allowed.";
        header("Refresh: 1; url=index.php"); 
        exit;
    }

    $event = new Model();
    $event->insert($file);
    header("Location: index.php"); 
    exit;
} else {
    echo "No file uploaded.";
    header("Refresh: 1; url=index.php"); 
    exit;
}
?>
