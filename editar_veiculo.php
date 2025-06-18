<?php
require_once 'includes/db.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// Buscar dados do veículo
$stmt = $pdo->prepare("SELECT * FROM veiculos WHERE id = ?");
$stmt->execute([$id]);
$veiculo = $stmt->fetch();

if (!$veiculo) {
    echo "Veículo não encontrado.";
    exit;
}

// Buscar categorias
$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];
    $ano = $_POST['ano'];
    $placa = $_POST['placa'];
    $cor = $_POST['cor'];
    $id_categoria = $_POST['id_categoria'];

    $imagem = $veiculo['imagem']; // manter imagem atual
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $imagem = 'uploads/' . uniqid() . '_' . $_FILES['imagem']['name'];
        move_uploaded_file($_FILES['imagem']['tmp_name'], $imagem);
    }

    $stmt = $pdo->prepare("UPDATE veiculos SET modelo = ?, marca = ?, ano = ?, placa = ?, cor = ?, id_categoria = ?, imagem = ? WHERE id = ?");
    $stmt->execute([$modelo, $marca, $ano, $placa, $cor, $id_categoria, $imagem, $id]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Veículo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4">Editar Veículo</h1>
    <form method="POST" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Modelo</label>
            <input type="text" name="modelo" class="form-control" value="<?= htmlspecialchars($veiculo['modelo']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Marca</label>
            <input type="text" name="marca" class="form-control" value="<?= htmlspecialchars($veiculo['marca']) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Ano</label>
            <input type="number" name="ano" class="form-control" value="<?= htmlspecialchars($veiculo['ano']) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Placa</label>
            <input type="text" name="placa" class="form-control" value="<?= htmlspecialchars($veiculo['placa']) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Cor</label>
            <input type="text" name="cor" class="form-control" value="<?= htmlspecialchars($veiculo['cor']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Categoria</label>
            <select name="id_categoria" class="form-select" required>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $veiculo['id_categoria'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Nova Imagem (opcional)</label>
            <input type="file" name="imagem" class="form-control" accept="image/*">
            
        </div>
        <div class="col-12">
            <button class="btn btn-primary" type="submit">Salvar Alterações</button>
            <a href="cadastrar_veiculo.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
