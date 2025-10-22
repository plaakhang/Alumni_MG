<?php
function flash(string $type, string $msg): void { 
  if(session_status()!==PHP_SESSION_ACTIVE) session_start();
  $_SESSION['__flash'][] = ['type'=>$type,'msg'=>$msg];
}
function consume_flash(): array {
  if(session_status()!==PHP_SESSION_ACTIVE) session_start();
  $f = $_SESSION['__flash'] ?? []; unset($_SESSION['__flash']); return $f;
}
function render_flash(){
  foreach(consume_flash() as $f){
    $cls = $f['type']==='ok' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200';
    echo "<div class='my-3 px-4 py-2 border rounded $cls'>".htmlspecialchars($f['msg'])."</div>";
  }
}
function header_html(string $title='Alumni WebApp'){
  $dark = "<script>try{if(localStorage.theme==='dark'||(!('theme'in localStorage)&&window.matchMedia('(prefers-color-scheme: dark)').matches)){document.documentElement.classList.add('dark')}else{document.documentElement.classList.remove('dark')}}catch(e){}</script>";
  echo "<!doctype html><html lang='th'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'>";
  echo "<title>".htmlspecialchars($title)."</title>";
  echo "<script src='https://cdn.tailwindcss.com'></script>$dark";
  echo "<style>:root{color-scheme:light dark}</style>";
  echo "</head><body class='bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100'>";
}
function navbar(?array $user){
  echo "<nav class='sticky top-0 z-10 backdrop-blur bg-white/70 dark:bg-slate-900/60 border-b border-slate-200/60 dark:border-slate-700/60'>
    <div class='max-w-5xl mx-auto px-4 h-14 flex items-center justify-between'>
      <a href='/' class='inline-flex items-center gap-2 font-semibold text-sky-600 dark:text-sky-400'>Alumni WebApp</a>
      <div class='flex items-center gap-3 text-sm'>
        <a class='hover:opacity-90' href='/posts.php'>Posts</a>
        <a class='hover:opacity-90' href='/news.php'>News</a>
        <a class='hover:opacity-90' href='/search_member.php'>ค้นหา</a>";
  if($user){
    echo "<a class='hover:opacity-90' href='/profile.php'>Profile</a>
          <a class='px-3 py-1.5 rounded bg-sky-600 text-white' href='/logout.php'>Logout</a>";
  } else {
    echo "<a class='px-3 py-1.5 rounded bg-sky-600 text-white' href='/login.php'>Login</a>
          <a class='px-3 py-1.5 rounded border' href='/register.php'>Register</a>";
  }
  echo "  </div></div></nav>";
}
function footer_html(){ echo "</body></html>"; }

function time_ago_th(string $ts): string {
  $t = strtotime($ts); if(!$t) return $ts;
  $d = time()-$t;
  if($d<60) return 'เมื่อสักครู่';
  if($d<3600) return intval($d/60).' นาทีที่แล้ว';
  if($d<86400) return intval($d/3600).' ชั่วโมงที่แล้ว';
  return date('Y-m-d H:i', $t);
}
