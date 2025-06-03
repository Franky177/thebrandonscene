
<?php
$approvedAds = glob('ads/approved/*.json');

echo "<style>
body {
  font-family: 'Segoe UI', sans-serif;
  background: #f0f2f5;
  margin: 0;
  padding: 0;
}
section {
  max-width: 1200px;
  margin: 0 auto;
  padding: 40px 20px;
}
h2 {
  text-align: center;
  color: #800020;
  font-size: 2rem;
  margin-bottom: 30px;
}
.scroll-container {
  display: flex;
  overflow-x: auto;
  gap: 20px;
  padding: 10px;
}
.scroll-container::-webkit-scrollbar {
  height: 8px;
}
.scroll-container::-webkit-scrollbar-thumb {
  background-color: #aaa;
  border-radius: 4px;
}
.ad-card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  min-width: 280px;
  max-width: 320px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  flex: 0 0 auto;
}
.ad-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 8px;
  margin: 10px 0;
}
.ad-card h4 {
  margin: 0;
  font-size: 1.2rem;
  color: #333;
}
.ad-card em {
  color: #666;
  font-size: 0.9rem;
}
.ad-card p {
  font-size: 0.95rem;
  color: #444;
  margin-top: 10px;
}
.ad-card a {
  display: inline-block;
  margin-top: 10px;
  background: #800020;
  color: white;
  padding: 8px 12px;
  border-radius: 6px;
  text-decoration: none;
  font-size: 0.9rem;
}
.grid-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
}
</style>";

if (count($approvedAds) === 0) {
  echo "<p style='text-align:center;'>No ads to display at the moment.</p>";
  return;
}

// --- Featured Ads Horizontal Scroll ---
echo "<section>";
echo "<h2>Featured Ads</h2>";
echo "<div class='scroll-container'>";
foreach ($approvedAds as $file) {
  $ad = json_decode(file_get_contents($file), true);
  echo "<div class='ad-card'>";
  echo "<h4>{$ad['business']}</h4>";
  echo "<em>{$ad['category']}</em>";
  echo "<img src='{$ad['image']}' alt='Ad image'>";
  echo "<p>{$ad['message']}</p>";
  if (!empty($ad['link'])) {
    echo "<a href='{$ad['link']}' target='_blank'>Visit</a>";
  }
  echo "</div>";
}
echo "</div></section>";

// --- Community Promotions Grid ---
echo "<section>";
echo "<h2>Community Promotions</h2>";
echo "<div class='grid-container'>";
foreach ($approvedAds as $file) {
  $ad = json_decode(file_get_contents($file), true);
  echo "<div class='ad-card'>";
  echo "<h4>{$ad['business']}</h4>";
  echo "<em>{$ad['category']}</em>";
  echo "<img src='{$ad['image']}' alt='Ad image'>";
  echo "<p>{$ad['message']}</p>";
  if (!empty($ad['link'])) {
    echo "<a href='{$ad['link']}' target='_blank'>Visit</a>";
  }
  echo "</div>";
}
echo "</div></section>";
?>
