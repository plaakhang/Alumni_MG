<?php
require_once __DIR__ . '/lib/auth.php';
require_guest();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ok = login_user($_POST['email'] ?? '', $_POST['password'] ?? '');
  if ($ok) { header('Location: /profile.php'); exit; }
}
$flash = consume_flash();
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
  <nav class="nav">
    <a href="/">Home</a>
    <a href="/register.php">Register</a>
  </nav>

  <?php foreach($flash as $f): ?>
    <div class="flash <?=htmlspecialchars($f['type'])?>"><?=htmlspecialchars($f['msg'])?></div>
  <?php endforeach; ?>

  <div class="card">
    <h2>เข้าสู่ระบบ</h2>
    <form method="post">
      <div><input type="email" name="email" placeholder="อีเมล" required></div>
      <div><input type="password" name="password" placeholder="รหัสผ่าน" required></div>
      <button type="submit">เข้าสู่ระบบ</button>
    </form>
    <p>ลองด้วยบัญชีตัวอย่าง: <code>test@example.com</code> / <code>test1234</code></p>
  </div>
</body>
</html>
