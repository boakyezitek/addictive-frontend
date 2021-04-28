<?php
$file = 'apple-app-site-association';
if (file_exists($file)) {
    header('Content-Type: application/json');
    readfile($file);
    exit;
}
?>