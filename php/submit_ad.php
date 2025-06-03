<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $business = htmlspecialchars($_POST['business']);
    $email = htmlspecialchars($_POST['email']);
    $category = htmlspecialchars($_POST['category']);
    $message = htmlspecialchars($_POST['message']);
    $link = isset($_POST['link']) ? htmlspecialchars($_POST['link']) : '';

    $image = $_FILES['image'];
    $imagePath = 'ads/pending/' . basename($image['name']);

    if (!file_exists('ads/pending')) {
        mkdir('ads/pending', 0777, true);
    }

    if (move_uploaded_file($image['tmp_name'], $imagePath)) {
        $adId = uniqid('ad_', true);
        $adData = [
            'id' => $adId,
            'business' => $business,
            'email' => $email,
            'category' => $category,
            'message' => $message,
            'link' => $link,
            'image' => $imagePath,
            'status' => 'pending',
            'expire_after_days' => isset($_POST['duration']) ? intval($_POST['duration']) : 30,
            'submitted_at' => date('Y-m-d H:i:s')
        ];

        file_put_contents("ads/pending/{$adId}.json", json_encode($adData, JSON_PRETTY_PRINT));
        // Email notification to admin
        $to = "thebrandonscene@gmail.com";
        $subject = "New Ad Submission from {$business}";
        $headers = "From: noreply@thebrandonscene.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8";

        $body = "A new ad has been submitted:\n\n"
              . "Business: $business\n"
              . "Email: $email\n"
              . "Category: $category\n"
              . "Message: $message\n"
              . "Link: $link\n";

        mail($to, $subject, $body, $headers);

        echo "<h2 style='text-align:center'>Your ad has been submitted and is pending approval.</h2>";
echo "<script>setTimeout(function() { window.location.href = 'index.html'; }, 4000);</script>";
    } else {
        echo "<h2 style='text-align:center;color:red;'>Failed to upload image. Please try again.</h2>";
    }
} else {
    echo "<h2 style='text-align:center;color:red;'>Invalid request method.</h2>";
}
?>
