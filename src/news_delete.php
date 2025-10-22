<?php
require_once __DIR__.'/../lib/auth.php';
require_once __DIR__.'/../lib/db.php';
require_once __DIR__.'/../lib/ui.php';
require_once __DIR__.'/../lib/roles.php';
require_auth();
$id=(int)($_GET['id']??0); $pdo=db();
$st=$pdo->prepare("SELECT * FROM news WHERE id=?"); $st->execute([$id]); $n=$st->fetch();
if($n && can_manage_news($n)){ $pdo->prepare("DELETE FROM news WHERE id=?")->execute([$id]); flash('ok','ลบข่าวแล้ว'); }
else { flash('err','ไม่มีสิทธิ์ลบหรือไม่พบข่าว'); }
header('Location:/news.php'); exit;
