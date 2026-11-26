<?php
// Sesuaikan dengan MySQL lokal (XAMPP: user root, password kosong).
const DB_HOST = 'localhost';
const DB_NAME = 'campus_portal';
const DB_USER = 'root';
const DB_PASS = '';

session_start();

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }
    return $pdo;
}

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

function require_login(): void {
    if (empty($_SESSION['uid'])) { header('Location: index.php'); exit; }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}

function csrf_check(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(400); exit('Token tidak valid.'); }
}

function page_head(string $title): void { ?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> — Portal Kampus</title>
<link rel="stylesheet" href="assets/style.css"></head><body>
<?php }
