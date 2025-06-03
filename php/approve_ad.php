<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ad_file"])) {
    $file = basename($_POST["ad_file"]);
    $source = "ads/pending/" . $file;
    $destinationDir = "ads/approved/";
    if (!is_dir($destinationDir)) mkdir($destinationDir, 0755, true);
    $destination = $destinationDir . $file;
    if (file_exists($source)) {
        rename($source, $destination);
    }
}
header("Location: admin_dashboard.php");
?>