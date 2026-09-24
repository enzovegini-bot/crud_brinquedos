<?php
require_once __DIR__ . '/funcoes.php';

$id = $_GET['id'] ?? null;
if (!is_scalar($id) || filter_var($id, FILTER_VALIDATE_INT) === false || (int) $id < 1) {
    header("Location: ../index.php");
    exit;
}

try {
    $brinquedo = buscarBrinquedoPorId($pdo, $id);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    die('Não foi possível carregar os dados do brinquedo. Tente novamente mais tarde.');
}
if (!$brinquedo) {
    die("Brinquedo não encontrado.");
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = $_POST;
    $erros = validarDadosBrinquedo($dados);

    if (empty($erros)) {
        try {
            if (editarBrinquedo($pdo, $id, $dados)) {
                header("Location: ../index.php");
                exit;
            }
            $erros[] = 'Não foi possível atualizar o brinquedo. Tente novamente.';
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $erros[] = 'O banco de dados não conseguiu atualizar o brinquedo.';
        }
    }
    $brinquedo = $dados;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Brinquedo</title>
</head>
<body>
    <h1>Editar Brinquedo</h1>

    <?php if (!empty($erros)): ?>
        <ul style="color:red;">
            <?php foreach ($erros as $erro): ?>
                <li><?= htmlspecialchars($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST">
        <label>Nome: <input type="text" name="nome" value="<?= htmlspecialchars($brinquedo['nome']) ?>" required></label><br><br>
        <label>Categoria: <input type="text" name="categoria" value="<?= htmlspecialchars($brinquedo['categoria']) ?>" required></label><br><br>
        <label>Faixa etária: <input type="text" name="faixa_etaria" value="<?= htmlspecialchars($brinquedo['faixa_etaria']) ?>" required></label><br><br>
        <label>Preço: <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($brinquedo['preco']) ?>" required></label><br><br>
        <label>Quantidade em estoque: <input type="number" name="quantidade_estoque" value="<?= htmlspecialchars($brinquedo['quantidade_estoque']) ?>" required></label><br><br>
        <button type="submit">Atualizar</button>
    </form>

    <p><a href="index.php">Voltar</a></p>
</body>
</html>