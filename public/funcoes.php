<?php
require_once __DIR__ . '/../infra/conexao.php';

function listarBrinquedos($pdo) {
    $stmt = $pdo->prepare("SELECT id, nome, categoria, faixa_etaria, preco, quantidade_estoque FROM brinquedos ORDER BY nome");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarBrinquedoPorId($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM brinquedos WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function validarDadosBrinquedo($dados) {
    $erros = [];
    foreach (['nome' => 100, 'categoria' => 50, 'faixa_etaria' => 30] as $campo => $limite) {
        $valor = $dados[$campo] ?? null;
        if (!is_string($valor) || trim($valor) === '') {
            $erros[] = ucfirst(str_replace('_', ' ', $campo)) . ' é obrigatório.';
        } elseif (function_exists('mb_strlen') ? mb_strlen(trim($valor), 'UTF-8') > $limite : strlen(trim($valor)) > $limite) {
            $erros[] = ucfirst(str_replace('_', ' ', $campo)) . " deve ter no máximo {$limite} caracteres.";
        }
    }

    $preco = $dados['preco'] ?? null;
    if (!is_scalar($preco) || !preg_match('/^\d{1,8}(?:\.\d{1,2})?$/', (string) $preco)) {
        $erros[] = 'Preço inválido.';
    }

    $quantidade = $dados['quantidade_estoque'] ?? null;
    if (!is_scalar($quantidade) || filter_var($quantidade, FILTER_VALIDATE_INT) === false || (int) $quantidade < 0 || (int) $quantidade > 2147483647) {
        $erros[] = 'Quantidade em estoque deve ser um número inteiro maior ou igual a zero.';
    }
    return $erros;
}


function cadastrarBrinquedo($pdo, $dados) {
    $sql = "INSERT INTO brinquedos (nome, categoria, faixa_etaria, preco, quantidade_estoque) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        trim($dados['nome']),
        trim($dados['categoria']),
        trim($dados['faixa_etaria']),
        $dados['preco'],
        $dados['quantidade_estoque'],
    ]);
}

function editarBrinquedo($pdo, $id, $dados) {
    $sql = "UPDATE brinquedos SET nome = ?, categoria = ?, faixa_etaria = ?, preco = ?, quantidade_estoque = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        trim($dados['nome']),
        trim($dados['categoria']),
        trim($dados['faixa_etaria']),
        $dados['preco'],
        $dados['quantidade_estoque'],
        $id,
    ]);
}

function excluirBrinquedo($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM brinquedos WHERE id = ?");
    return $stmt->execute([$id]);
}