<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/db.php';
require_once __DIR__.'/../lib/ui.php';
require_auth(); start_session(); $pdo=db(); $user=current_user();
$name = trim($_GET['q'] ?? '');
$year = trim($_GET['y'] ?? '');
$bio  = trim($_GET['b'] ?? '');
$sql = "SELECT prefix, fullname, grad_year, bio FROM users WHERE 1=1";
$p = [];
if($name!==''){ $sql.=" AND (fullname LIKE ? OR display_name LIKE ?)"; $p[]="%$name%"; $p[]="%$name%"; }
if($year!==''){ $sql.=" AND grad_year=?"; $p[]=(int)$year; }
if($bio!==''){ $sql.=" AND bio LIKE ?"; $p[]="%$bio%"; }
$sql.=" ORDER BY grad_year DESC, fullname ASC LIMIT 100";
$st=$pdo->prepare($sql); $st->execute($p); $rows=$st->fetchAll();
header_html('ค้นหาศิษย์เก่า'); navbar($user);
?>
<div class="max-w-4xl mx-auto p-4">
  <h1 class="text-xl font-semibold mb-3">ค้นหาศิษย์เก่า</h1>
  <form class="grid sm:grid-cols-3 gap-3 mb-4">
    <input name="q" value="<?= htmlspecialchars($name) ?>" class="border px-3 py-2 rounded" placeholder="ชื่อ/นามสกุล">
    <input name="y" value="<?= htmlspecialchars($year) ?>" class="border px-3 py-2 rounded" placeholder="ปีที่จบ (พ.ศ.)">
    <input name="b" value="<?= htmlspecialchars($bio) ?>" class="border px-3 py-2 rounded" placeholder="คำค้นใน bio">
    <div class="sm:col-span-3">
      <button class="px-4 py-2 rounded bg-sky-600 text-white">ค้นหา</button>
    </div>
  </form>
  <div class="divide-y divide-slate-200 dark:divide-slate-700 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
    <?php if(!$rows): ?>
      <div class="p-4 text-slate-500">ยังไม่พบผลลัพธ์</div>
    <?php else: foreach($rows as $r): ?>
      <div class="p-4">
        <div class="font-medium"><?= htmlspecialchars(trim(($r['prefix']??'').' '.($r['fullname']??''))) ?: '—' ?></div>
        <div class="text-sm text-slate-500">ปีที่จบ: <?= htmlspecialchars($r['grad_year'] ?? '—') ?></div>
        <div class="mt-1 text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line"><?= htmlspecialchars($r['bio'] ?? '—') ?></div>
      </div>
    <?php endforeach; endif; ?>
  </div>
</div>
<?php footer_html();
