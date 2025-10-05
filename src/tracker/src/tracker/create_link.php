<?php
require_once 'config.php';

if ($_POST['url']) {
    $original_url = $_POST['url'];
    $unique_id = md5(uniqid(rand(), true));
    
    $stmt = $pdo->prepare("INSERT INTO tracked_links (unique_id, original_url) VALUES (?, ?)");
    $stmt->execute([$unique_id, $original_url]);
    
    $tracking_url = "http://" . $_SERVER['HTTP_HOST'] . "/tracker/track.php?id=" . $unique_id;
    
    echo "<h2>Tracking Link Created:</h2>";
    echo "<p><a href='$tracking_url'>$tracking_url</a></p>";
    echo "<p>Original URL: $original_url</p>";
} else {
?>
<html>
<body>
<h2>Create Tracking Link</h2>
<form method="post">
    <label>URL to track: <input type="text" name="url" size="50"></label><br><br>
    <input type="submit" value="Create Tracking Link">
</form>
</body>
</html>
<?php
}
?>
