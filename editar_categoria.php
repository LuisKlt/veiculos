<?php
require_once 'includes/db.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: cadastrar_categoria.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = ?");
$stmt->execute([$id]);
$categoria = $stmt->fetch();

if (!$categoria) {
    echo "Categoria não encontrada.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $stmt = $pdo->prepare("UPDATE categorias SET nome = ? WHERE id = ?");
    $stmt->execute([$nome, $id]);
    header('Location: cadastrar_categoria.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4">Editar Categoria</h1>
    <form method="POST" class="needs-validation" novalidate>
        <div class="mb-3">
            <label class="form-label">Nome da Categoria</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($categoria['nome']) ?>" required>
            <div class="invalid-feedback">Informe o nome da categoria.</div>
        </div>
        <button class="btn btn-primary" type="submit">Salvar Alterações</button>
        <a href="cadastrar_categoria.php" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
</body>
</html>
