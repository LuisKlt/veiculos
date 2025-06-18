<?php
require_once 'includes/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM veiculos WHERE id = ?");
$stmt->execute([$id]);
$v = $stmt->fetch();

if (!$v) {
    echo "<div class='alert alert-danger'>Veículo não encontrado.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detalhes do Veículo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container my-5">
    <a href="index.php" class="btn btn-secondary mb-4">← Voltar</a>

    <div class="card shadow">
        <?php if ($v['imagem']): ?>
            <img src="<?= htmlspecialchars($v['imagem']) ?>" class="card-img-top img-fluid" alt="<?= htmlspecialchars($v['modelo']) ?>">
        <?php endif; ?>
        <div class="card-body">
            <h2 class="card-title"><?= htmlspecialchars($v['modelo']) ?></h2>
            <ul class="list-group list-group-flush my-3">
                <li class="list-group-item"><strong>Marca:</strong> <?= htmlspecialchars($v['marca']) ?></li>
                <li class="list-group-item"><strong>Ano:</strong> <?= htmlspecialchars($v['ano']) ?></li>
                <li class="list-group-item"><strong>Cor:</strong> <?= htmlspecialchars($v['cor']) ?></li>
                <li class="list-group-item"><strong>Placa:</strong> <?= htmlspecialchars($v['placa']) ?></li>
            </ul>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
