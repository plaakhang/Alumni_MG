<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/db.php';
require_once __DIR__.'/../lib/ui.php';
require_auth(); start_session();
$pdo = db();
$u = current_user();
$stmt = $pdo->prepare("SELECT email,display_name,avatar_path,bio,prefix,fullname,phone,address_user,grad_year,created_at FROM users WHERE id=?");
$stmt->execute([$u['id']]); $me = $stmt->fetch();
header_html('Profile'); navbar($u); render_flash();
$avatar = $me['avatar_path'] ?: 'https://cdn-icons-png.flaticon.com/512/9815/9815472.png';
?>
<div class="max-w-3xl mx-auto p-6">
  <div class="flex items-center gap-4">
    <img src="<?= htmlspecialchars($avatar) ?>" class="w-20 h-20 rounded-full object-cover border" alt="">
    <div>
      <div class="text-xl font-semibold"><?= htmlspecialchars($me['display_name']) ?></div>
      <div class="text-slate-500 dark:text-slate-400"><?= htmlspecialchars($me['email']) ?></div>
      <a href="/profile_edit.php" class="mt-2 inline-block px-3 py-1.5 rounded border">แก้ไขโปรไฟล์</a>
    </div>
  </div>
  <div class="mt-6 grid sm:grid-cols-2 gap-4 text-sm">
    <div><b>ชื่อเต็ม</b><div><?= htmlspecialchars(trim(($me['prefix']??'').' '.($me['fullname']??''))) ?: '—' ?></div></div>
    <div><b>ปีที่จบ</b><div><?= htmlspecialchars($me['grad_year'] ?? '—') ?></div></div>
    <div><b>เบอร์โทร</b><div><?= htmlspecialchars($me['phone'] ?? '—') ?></div></div>
    <div><b>ที่อยู่</b><div class="whitespace-pre-line"><?= htmlspecialchars($me['address_user'] ?? '—') ?></div></div>
    <div class="sm:col-span-2"><b>เกี่ยวกับฉัน</b><div class="whitespace-pre-line"><?= htmlspecialchars($me['bio'] ?? '—') ?></div></div>
  </div>
</div>
<?php footer_html();
