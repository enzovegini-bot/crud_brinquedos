<?php
require_once __DIR__ . '/funcoes.php';

$id = $_GET['id'] ?? null;

if (!is_scalar($id) || filter_var($id, FILTER_VALIDATE_INT) === false || (int) $id < 1) {
    header("Location: ../index.php");
    exit;
}

try {
    excluirBrinquedo($pdo, $id);
} catch (PDOException $e) {
    error_log($e->getMessage());
}

header("Location: ../index.php");
exit;