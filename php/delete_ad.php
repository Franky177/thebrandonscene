<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ad_file"])) {
    $file = basename($_POST["ad_file"]);
    $filePath = "ads/pending/" . $file;
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}
header("Location: admin_dashboard.php");
?>