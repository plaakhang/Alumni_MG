<?php
require_once __DIR__ . '/db.php';

function start_session(): void {
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
}

function flash($type, $msg) {
  start_session();
  $_SESSION['flash'][] = ['type'=>$type, 'msg'=>$msg];
}

function consume_flash() {
  start_session();
  $f = $_SESSION['flash'] ?? [];
  unset($_SESSION['flash']);
  return $f;
}

function current_user() {
  start_session();
  return $_SESSION['user'] ?? null;
}

function require_guest() {
  if (current_user()) {
    header('Location: /profile.php'); exit;
  }
}

function require_auth() {
  if (!current_user()) {
    header('Location: /login.php'); exit;
  }
}

function register_user($email, $password, $display_name): bool {
  $email = trim(strtolower($email));
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash('err', 'อีเมลไม่ถูกต้อง'); return false;
  }
  if (strlen($password) < 8) {
    flash('err', 'รหัสผ่านอย่างน้อย 8 ตัวอักษร'); return false;
  }
  if (!$display_name) {
    flash('err', 'กรุณาระบุชื่อที่แสดง'); return false;
  }

  $pdo = db();
  $stmt = $pdo->prepare('SELECT id FROM users WHERE email=?');
  $stmt->execute([$email]);
  if ($stmt->fetch()) {
    flash('err', 'อีเมลนี้ถูกใช้แล้ว'); return false;
  }

  $hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare('INSERT INTO users (email, password_hash, display_name) VALUES (?,?,?)');
  $stmt->execute([$email, $hash, $display_name]);
  flash('ok', 'สมัครสำเร็จ! เข้าสู่ระบบได้เลย');
  return true;
}

function login_user($email, $password): bool {
  $email = trim(strtolower($email));
  $pdo = db();
  $stmt = $pdo->prepare('SELECT * FROM users WHERE email=?');
  $stmt->execute([$email]);
  $u = $stmt->fetch();
  if (!$u || !password_verify($password, $u['password_hash'])) {
    flash('err', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'); return false;
  }
  start_session();
  $_SESSION['user'] = ['id'=>$u['id'], 'email'=>$u['email'], 'display_name'=>$u['display_name']];
  flash('ok', 'ยินดีต้อนรับกลับมา!');
  return true;
}

function logout_user() {
  start_session();
  $_SESSION = [];
  session_destroy();
}
