<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["event_file"])) {
    $file = basename($_POST["event_file"]);
    $source = "submitted-events/" . $file;
    $destinationDir = "approved-events/";
    if (!is_dir($destinationDir)) mkdir($destinationDir);
    $destination = $destinationDir . $file;

    if (file_exists($source)) {
        rename($source, $destination);

        $prefix = pathinfo($file, PATHINFO_FILENAME);
        foreach (glob("submitted-events/{$prefix}.*") as $img) {
            if ($img !== $source) {
                rename($img, $destinationDir . basename($img));
            }
        }
    }
}
header("Location: admin_dashboard.php");
?>