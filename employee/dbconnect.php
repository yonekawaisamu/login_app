<?php
try {
    $db = new PDO(
        'mysql:dbname=login_app;host:127.0.0.1;charset=utf8mb4',
        'root',
        '',
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );
} catch (EXception $e) {
    die($e->getMessage());
}