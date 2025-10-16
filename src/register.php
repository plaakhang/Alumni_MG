<?php
require_once __DIR__ . '/lib/auth.php';
require_guest();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ok = register_user($_POST['email'] ?? '', $_POST['password'] ?? '', $_POST['display_name'] ?? '');
  if ($ok) { header('Location: /login.php'); exit; }
}
$flash = consume_flash();
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>Register</title>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
  <nav class="nav">
    <a href="/">Home</a>
    <a href="/login.php">Login</a>
  </nav>

  <?php foreach($flash as $f): ?>
    <div class="flash <?=htmlspecialchars($f['type'])?>"><?=htmlspecialchars($f['msg'])?></div>
  <?php endforeach; ?>

  <div class="card">
    <h2>สมัครสมาชิก</h2>
    <form method="post">
      <div><input type="text" name="display_name" placeholder="ชื่อที่แสดง" required></div>
      <div><input type="email" name="email" placeholder="อีเมล" required></div>
      <div><input type="password" name="password" placeholder="รหัสผ่าน (>= 8 ตัวอักษร)" required></div>
      <button type="submit">สมัคร</button>
    </form>
  </div>
</body>
</html>
