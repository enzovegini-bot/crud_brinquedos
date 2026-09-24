<?php

$host = 'localhost';
$dbname = 'gestao_brinquedos';
$usuario = 'root';
$senha = '';
$porta = 3306;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    die("Não foi possível conectar ao banco de dados. Confira a configuração e tente novamente.");
}