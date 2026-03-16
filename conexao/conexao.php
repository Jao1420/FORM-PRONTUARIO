<?php

$path = __DIR__ . '/../.env';
if (file_exists($path)) {
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value, " \t\n\r\0\x0B;"); // Remove espaços e o ";"
    }
}

$host = $_ENV['DB_HOST']; 
$user = $_ENV['DB_USERNAME'];
$pass = $_ENV['DB_PASSWORD'];       
$db   = $_ENV['DB_NAME'];
$port = $_ENV['DB_PORT'];     


$conn = new mysqli($host, $user, $pass, $db, $port);

if($conn->connect_error){
    die("Falha na conexão: " . $conn->connect_error);
}
?>