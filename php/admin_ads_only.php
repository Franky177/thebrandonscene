<?php
session_start();

$admin_password = '12345';

if (!isset($_SESSION['logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['password'] === $admin_password) {
        $_SESSION['logged_in'] = true;
    } else {
        echo '<!DOCTYPE html>
        <html><head><title>Admin Login</title>
        <style>
        body { font-family: Segoe UI, sans-serif; background: linear-gradient(to right, #3f51b5, #9c27b0); color: white; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        form { background: white; color: black; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.2); }
        input[type="password"] { padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc; margin-top: 10px; }
        button { padding: 10px 20px; background: #5a2a82; color: white; border: none; border-radius: 5px; margin-top: 15px; width: 100%; }
        </style></head>
        <body>
        <form method="POST">
          <h2 style="color:#5a2a82; text-align:center;">Admin Login</h2>
          <input type="password" name="password" placeholder="Enter Password" required>
          <button type="submit">Login</button>
        </form></body></html>';
        exit;
    }
}

function isDuplicateAd($adData) {
    foreach (['pending', 'approved', 'rejected'] as $folder) {
        foreach (glob("ads/$folder/*.json") as $file) {
            $existing = json_decode(file_get_contents($file), true);
            if (
                $existing['business'] === $adData['business'] &&
                $existing['message'] === $adData['message'] &&
                $existing['image'] === $adData['image']
            ) {
                return true;
            }
        }
    }
    return false;
}

function moveAd($id, $from, $to) {
    $path = "ads/$from/$id.json";
    if (file_exists($path)) {
        $data = json_decode(file_get_contents($path), true);
        if (!isDuplicateAd($data)) {
            $data['status'] = $to;
            if (!file_exists("ads/$to")) {
                mkdir("ads/$to", 0777, true);
            }
            file_put_contents("ads/$to/$id.json", json_encode($data, JSON_PRETTY_PRINT));
        }
        unlink($path);
    }
}

if (isset($_GET['approve'])) {
    moveAd($_GET['approve'], 'pending', 'approved');
    header('Location: admin_dashboard.php?tab=pending');
    exit;
}
if (isset($_GET['reject'])) {
    moveAd($_GET['reject'], 'pending', 'rejected');
    header('Location: admin_dashboard.php?tab=pending');
    exit;
}
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    foreach (['pending', 'approved', 'rejected'] as $folder) {
        $path = "ads/$folder/$id.json";
        if (file_exists($path)) {
            unlink($path);
            break;
        }
    }
    header('Location: admin_dashboard.php?tab=approved');
    exit;
}

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'pending';
$ads = glob("ads/$tab/*.json");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #3f51b5, #9c27b0);
      color: #fff;
      margin: 0;
      padding: 20px;
    }
    .nav {
      text-align: center;
      margin-bottom: 30px;
    }
    .nav a {
      margin: 0 10px;
      padding: 10px 20px;
      text-decoration: none;
      background: #fff;
      color: #5a2a82;
      border-radius: 5px;
      font-weight: bold;
    }
    .nav a.active {
      background: #5a2a82;
      color: #fff;
    }
    .ad-box {
      background: #fff;
      color: #333;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 30px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .ad-box h3 {
      color: #5a2a82;
      margin-top: 0;
    }
    .ad-box img {
      max-width: 250px;
      border-radius: 8px;
      display: block;
      margin: 10px 0;
    }
    .ad-box a {
      text-decoration: none;
      margin-right: 15px;
    }
    .ad-box a.approve { color: #28a745; font-weight: bold; }
    .ad-box a.reject { color: #dc3545; font-weight: bold; }
    .ad-box a.delete { color: #fd7e14; font-weight: bold; }
    .status-expired { color: #d63384; font-weight: bold; }
  </style>
</head>
<body>
  <h1 style="text-align:center;">Manage Ads</h1>
  <div class="nav">
    <a href="?tab=pending" class="<?php echo $tab === 'pending' ? 'active' : ''; ?>">Pending</a>
    <a href="?tab=approved" class="<?php echo $tab === 'approved' ? 'active' : ''; ?>">Approved</a>
    <a href="?tab=rejected" class="<?php echo $tab === 'rejected' ? 'active' : ''; ?>">Rejected</a>
  </div>

  <?php
  foreach ($ads as $file) {
      $ad = json_decode(file_get_contents($file), true);
      $submitted = isset($ad['submitted_at']) ? new DateTime($ad['submitted_at']) : new DateTime('now');
      $now = new DateTime();
      $daysAgo = $submitted->diff($now)->days;
      $maxAge = isset($ad['expire_after_days']) ? $ad['expire_after_days'] : 30;
      $expired = $daysAgo > $maxAge;

      echo "<div class='ad-box'>";
      echo "<h3>{$ad['business']} ({$ad['category']})</h3>";
      echo "<p><strong>Email:</strong> {$ad['email']}<br>";
      echo "<strong>Message:</strong> {$ad['message']}<br>";
      if (!empty($ad['link'])) echo "<strong>Link:</strong> <a href='{$ad['link']}' target='_blank'>{$ad['link']}</a><br>";
      echo "<strong>Submitted:</strong> {$daysAgo} day(s) ago<br>";
      if ($expired) echo "<p class='status-expired'>Status: Expired</p>";
      echo "<img src='{$ad['image']}' alt='Ad image'>";
      if ($tab === 'pending') {
        echo "<a href='?approve={$ad['id']}' class='approve'>Approve</a>";
        echo "<a href='?reject={$ad['id']}' class='reject'>Reject</a>";
      }
      echo "<a href='?delete={$ad['id']}' class='delete'>Delete</a>";
      echo "</div>";
  }
  ?>
</body>
</html>