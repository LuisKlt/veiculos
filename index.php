<?php
session_start();

require_once 'includes/db.php';

$modelo = $_GET['modelo'] ?? '';
$ano = $_GET['ano'] ?? '';
$categoria_id = $_GET['categoria'] ?? '';

$query = "SELECT * FROM veiculos WHERE 1";
$params = [];

if ($modelo) {
    $query .= " AND modelo LIKE ?";
    $params[] = "%$modelo%";
}
if ($ano) {
    $query .= " AND ano LIKE ?";
    $params[] = "%$ano%";
}
if ($categoria_id) {
    $query .= " AND id_categoria = ?";
    $params[] = $categoria_id;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Oferta de Veículos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4">
  <div class="container d-flex justify-content-between align-items-center">
    <h1 class="mb-0 text-white"><span>Lima</span>Veículos</h1>
    
    <div class="d-flex gap-2">
      <?php if (isset($_SESSION['usuario_id'])): ?>
        <a class="btn btn-outline-light" href="cadastrar_usuario.php">Cadastrar Usuário</a>
        <a class="btn btn-danger" href="logout.php">Sair</a>
      <?php else: ?>
        <a class="btn btn-success" href="login.php">Entrar</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container py-4">
    

    <form method="GET" class="row g-2 mb-4">
        <div class="col-sm-4">
            <input type="text" name="modelo" class="form-control" placeholder="Buscar por modelo" value="<?= htmlspecialchars($modelo) ?>">
        </div>
        <div class="col-sm-2">
            <input type="text" name="ano" class="form-control" placeholder="Buscar por ano" value="<?= htmlspecialchars($ano) ?>">
        </div>
        <div class="col-sm-3">
            <select name="categoria" class="form-select">
                <option value="">Todas as categorias</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $categoria_id ? 'selected' : '' ?>><?= htmlspecialchars($cat['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="cadastrar_categoria.php" class="btn btn-outline-secondary">Categorias</a>
            <a href="cadastrar_veiculo.php" class="btn btn-outline-secondary">Veículos</a>
        </div>
    </form>

    <div class="row">
        <?php foreach ($veiculos as $v): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if ($v['imagem']): ?>
                        <img src="<?= htmlspecialchars($v['imagem']) ?>" class="card-img-top img-thumb" alt="<?= htmlspecialchars($v['modelo']) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($v['modelo']) ?></h5>
                        <p class="card-text">Marca: <?= htmlspecialchars($v['marca']) ?><br>Cor: <?= htmlspecialchars($v['cor']) ?><br>Ano: <?= htmlspecialchars($v['ano']) ?></p>
                        <a href="veiculo.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-primary">Ver detalhes</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
