<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/ui.php';
start_session();
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(login($_POST['email']??'', $_POST['password']??'')){ flash('ok','เข้าสู่ระบบสำเร็จ'); header('Location:/'); exit; }
  flash('err','อีเมลหรือรหัสผ่านไม่ถูกต้อง');
}
header_html('Login'); navbar(current_user()); render_flash();
?>
<div class="max-w-md mx-auto p-6">
  <h1 class="text-2xl font-semibold mb-4">เข้าสู่ระบบ</h1>
  <form method="post" class="space-y-3">
    <input name="email" type="email" required placeholder="อีเมล" class="w-full border px-3 py-2 rounded">
    <input name="password" type="password" required placeholder="รหัสผ่าน" class="w-full border px-3 py-2 rounded">
    <button class="px-4 py-2 rounded bg-sky-600 text-white">เข้าสู่ระบบ</button>
  </form>
</div>
<?php footer_html();
