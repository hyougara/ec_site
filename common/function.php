<?php
function e(string $str, string $charset = 'UTF-8'): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, $charset);
}

function sanitize(array $before): array {
    foreach($before as $key => $value) {
        $after[$key] = e($value);
    }
    return $after;
}

function dbConnect() {
    $dsn = "mysql:dbname=ec_site;host=localhost;charset=utf8";
    $user = "root";
    $password = "glad";

    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}
