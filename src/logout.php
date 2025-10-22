<?php
require_once __DIR__.'/../lib/ui.php';
start_session(); session_destroy(); flash('ok','ออกจากระบบแล้ว'); header('Location:/login.php'); exit;
