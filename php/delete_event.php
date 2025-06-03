<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["event_file"])) {
    $file = basename($_POST["event_file"]);
    $filePath = "submitted-events/" . $file;

    if (file_exists($filePath)) {
        unlink($filePath);
        $prefix = pathinfo($file, PATHINFO_FILENAME);
        foreach (glob("submitted-events/{$prefix}.*") as $img) {
            if ($img !== $filePath) unlink($img);
        }
    }
}
header("Location: admin_dashboard.php");
?>