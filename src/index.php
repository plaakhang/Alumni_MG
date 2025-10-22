<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/ui.php';
start_session();
$user = current_user();
header_html('Alumni WebApp'); navbar($user);
?>
<header class="max-w-5xl mx-auto px-4 mt-8">
  <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white/60 dark:bg-slate-800/60 shadow p-8">
    <h1 class="text-3xl font-semibold">ยินดีต้อนรับสู่ Alumni WebApp</h1>
    <p class="mt-2 text-slate-600 dark:text-slate-300">ระบบสำหรับศิษย์เก่าพบปะ โพสต์ ข่าวสาร และโปรไฟล์</p>
    <div class="mt-6 flex flex-wrap gap-3">
      <a href="/posts.php" class="px-4 py-2 rounded bg-sky-600 text-white">โพสต์</a>
      <a href="/news.php" class="px-4 py-2 rounded border">ข่าวสาร</a>
      <a href="/search_member.php" class="px-4 py-2 rounded border">ค้นหาศิษย์เก่า</a>
      <?php if($user): ?>
        <a href="/profile.php" class="px-4 py-2 rounded border">โปรไฟล์ของฉัน</a>
      <?php else: ?>
        <a href="/register.php" class="px-4 py-2 rounded border">สมัครสมาชิก</a>
      <?php endif; ?>
    </div>
  </div>
</header>
<?php footer_html();
