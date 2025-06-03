<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $date = $_POST["date"];
    $location = $_POST["location"];
    $description = $_POST["description"];

    $folder = "submitted-events/";
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    $timestamp = time();
    $filename = $folder . $timestamp . "-" . preg_replace("/[^a-zA-Z0-9]/", "-", $title);
    $textfile = $filename . ".txt";

    file_put_contents($textfile, "Title: $title
Date: $date
Location: $location
Description: $description");

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $target = $filename . "." . $ext;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target);
    }

    // Redirect to homepage after 3 seconds
    echo "<p>Your Event has been submitted and is pending approval. Redirecting to homepage...</p>";
    echo "<script>setTimeout(function() { window.location.href = '/index.html'; }, 4000);</script>";
}
?>