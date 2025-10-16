<?php
require_once __DIR__ . '/lib/auth.php';
$flash = consume_flash();
$user = current_user();
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>Alumni WebApp</title>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
  <nav class="nav">
    <a href="/">Home</a>
    <?php if ($user): ?>
      <a href="/profile.php">Profile</a>
      <a href="/logout.php">Logout</a>
    <?php else: ?>
      <a href="/register.php">Register</a>
      <a href="/login.php">Login</a>
    <?php endif; ?>
    <a href="http://localhost:8080" target="_blank">phpMyAdmin</a>
  </nav>

  <?php foreach($flash as $f): ?>
    <div class="flash <?=htmlspecialchars($f['type'])?>"><?=htmlspecialchars($f['msg'])?></div>
  <?php endforeach; ?>

  <div class="card">
    <h2>ยินดีต้อนรับสู่ Alumni WebApp (MVP)</h2>
    <p>โปรเจกต์นี้เป็นโครงสร้างพื้นฐานแบบง่ายสำหรับเริ่มทำ Web Application ด้วย PHP + MySQL บน Docker</p>
    <ul>
      <li>สมัครสมาชิก / เข้าสู่ระบบ</li>
      <li>หน้าโปรไฟล์อย่างง่าย</li>
      <li>จัดการ DB ผ่าน phpMyAdmin</li>
    </ul>
    <?php if ($user): ?>
      <p>สถานะ: เข้าสู่ระบบเป็น <strong><?=htmlspecialchars($user['display_name'])?></strong></p>
    <?php else: ?>
      <p>สถานะ: ยังไม่เข้าสู่ระบบ</p>
    <?php endif; ?>
  </div>
</body>
</html>
