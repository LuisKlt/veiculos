<?php
require_once 'includes/db.php';

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Cadastro de nova categoria
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $pdo->prepare("INSERT INTO categorias (nome) VALUES (?)")->execute([$nome]);
    header("Location: cadastrar_categoria.php");
    exit;
}

// Buscar todas as categorias
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cadastrar Categoria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4">Cadastro de Categoria</h1>

    <form method="POST" class="needs-validation mb-5" novalidate>
        <div class="mb-3">
            <label for="nome" class="form-label">Nome da Categoria</label>
            <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome da Categoria" required>
            <div class="invalid-feedback">
                Por favor, informe o nome da categoria.
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar Categoria</button>
        <a href="index.php" class="btn btn-secondary ms-2">Cancelar</a>
    </form>

    <h2 class="mb-3">Categorias Cadastradas</h2>
    <?php if ($categorias): ?>
        <table class="table table-dark table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $cat): ?>
                    <tr>
                        <td><?= htmlspecialchars($cat['id']) ?></td>
                        <td><?= htmlspecialchars($cat['nome']) ?></td>
                        <td>
                            <a href="editar_categoria.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="excluir_categoria.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma categoria cadastrada.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()
</script>
</body>
</html>
