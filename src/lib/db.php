<?php
function db(): PDO {
  static $pdo = null;
  if ($pdo) return $pdo;

  $host = getenv('DB_HOST') ?: 'db';
  $name = getenv('DB_NAME') ?: 'alumni_db';
  $user = getenv('DB_USER') ?: 'app';
  $pass = getenv('DB_PASS') ?: 'apppass';

  $dsn = "mysql:host={$host};dbname={$name};charset=utf8mb4";
  $opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ];
  $pdo = new PDO($dsn, $user, $pass, $opt);
  return $pdo;
}
