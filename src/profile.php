<?php
require_once __DIR__ . '/lib/auth.php';
require_auth();
$flash = consume_flash();
$user = current_user();
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>Profile</title>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
  <nav class="nav">
    <a href="/">Home</a>
    <a href="/logout.php">Logout</a>
  </nav>

  <?php foreach($flash as $f): ?>
    <div class="flash <?=htmlspecialchars($f['type'])?>"><?=htmlspecialchars($f['msg'])?></div>
  <?php endforeach; ?>

  <div class="card">
    <h2>โปรไฟล์</h2>
    <p><strong>ชื่อที่แสดง:</strong> <?=htmlspecialchars($user['display_name'])?></p>
    <p><strong>อีเมล:</strong> <?=htmlspecialchars($user['email'])?></p>
    <p>(หน้านี้เป็นจุดเริ่ม — ต่อไปอาจเพิ่มแก้ไขโปรไฟล์, รูปภาพ, ข่าวสาร ฯลฯ)</p>
  </div>
</body>
</html>
