<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/ui.php';
start_session();
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(register_user($_POST)){ flash('ok','สมัครสมาชิกสำเร็จ เข้าสู่ระบบได้เลย'); header('Location:/login.php'); exit; }
  else flash('err','กรอกข้อมูลให้ครบ (รหัสผ่าน ≥ 6)');
}
header_html('Register'); navbar(current_user()); render_flash();
?>
<div class="max-w-md mx-auto p-6">
  <h1 class="text-2xl font-semibold mb-4">สมัครสมาชิก</h1>
  <form method="post" class="space-y-3">
    <input name="email" type="email" required placeholder="อีเมล" class="w-full border px-3 py-2 rounded">
    <input name="display_name" required placeholder="ชื่อที่แสดง" class="w-full border px-3 py-2 rounded">
    <input name="password" type="password" required placeholder="รหัสผ่าน (≥ 6)" class="w-full border px-3 py-2 rounded">
    <button class="px-4 py-2 rounded bg-sky-600 text-white">สมัคร</button>
  </form>
</div>
<?php footer_html();
