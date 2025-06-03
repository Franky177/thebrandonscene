<?php
session_start();
if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right, #4e54c8, #8f94fb);
      color: white;
      padding: 20px;
    }
    .tabs {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
    }
    .tabs button {
      padding: 10px 20px;
      margin: 0 5px;
      background: white;
      color: #4e54c8;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }
    .tabs button.active {
      background: #2c2c54;
      color: white;
    }
    .section {
      display: none;
    }
    .section.active {
      display: block;
    }
    .card {
      background: white;
      color: black;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 8px;
    }
    .card img {
      max-width: 100%;
      margin-top: 10px;
      border-radius: 6px;
    }
    .card button {
      margin-right: 10px;
      padding: 6px 12px;
      background-color: #4e54c8;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <h2>🎛️ Admin Dashboard</h2>
  <div class="tabs">
    <button onclick="showSection('ads')" id="adsBtn" class="active">📢 Manage Ads</button>
    <button onclick="showSection('events')" id="eventsBtn">📅 Manage Events</button>
    <a href="logout.php" style="margin-left:auto;"><button style="background:red;">Logout</button></a>
  </div>

  <div id="ads" class="section active">
    <h3>📢 Submitted Ads</h3>
    <?php
    $adsDir = "ads/pending";
    if (is_dir($adsDir)) {
        $files = array_diff(scandir($adsDir), array('.', '..'));
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'txt') {
                $base = pathinfo($file, PATHINFO_FILENAME);
                $filePath = "$adsDir/$file";
                echo "<div class='card'><pre>" . htmlspecialchars(file_get_contents($filePath)) . "</pre>";
                echo "<form method='POST' action='approve_ad.php' style='display:inline;'>
                        <input type='hidden' name='ad_file' value='$file'>
                        <button type='submit'>✅ Approve</button>
                      </form>
                      <form method='POST' action='delete_ad.php' style='display:inline;'>
                        <input type='hidden' name='ad_file' value='$file'>
                        <button type='submit'>🗑️ Delete</button>
                      </form></div>";
            }
        }
    }
    ?>
  </div>

  <div id="events" class="section">
    <h3>📅 Submitted Events</h3>
    <?php
    $eventDir = "submitted-events";
    if (is_dir($eventDir)) {
        $files = array_diff(scandir($eventDir), array('.', '..'));
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'txt') {
                $base = pathinfo($file, PATHINFO_FILENAME);
                $filePath = "$eventDir/$file";
                echo "<div class='card'><pre>" . htmlspecialchars(file_get_contents($filePath)) . "</pre>";

                $imageTypes = ['jpg', 'jpeg', 'png', 'gif'];
                foreach ($imageTypes as $ext) {
                    foreach (glob("$eventDir/$base*.$ext") as $imagePath) {
                        $imgUrl = "$eventDir/" . basename($imagePath);
                        echo "<img src='$imgUrl' alt='Event Image' />";
                        break 2;
                    }
                }

                echo "<form method='POST' action='approve_event.php' style='display:inline;'>
                        <input type='hidden' name='event_file' value='$file'>
                        <button type='submit'>✅ Approve</button>
                      </form>
                      <form method='POST' action='delete_event.php' style='display:inline;'>
                        <input type='hidden' name='event_file' value='$file'>
                        <button type='submit'>🗑️ Delete</button>
                      </form></div>";
            }
        }
    }
    ?>
  </div>

  <script>
    function showSection(section) {
      document.getElementById("ads").classList.remove("active");
      document.getElementById("events").classList.remove("active");
      document.getElementById("adsBtn").classList.remove("active");
      document.getElementById("eventsBtn").classList.remove("active");

      document.getElementById(section).classList.add("active");
      document.getElementById(section + "Btn").classList.add("active");
    }
  </script>
</body>
</html>