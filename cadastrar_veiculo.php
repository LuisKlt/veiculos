<?php
require_once 'includes/db.php';

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];
    $ano = $_POST['ano'];
    $placa = $_POST['placa'];
    $cor = $_POST['cor'];
    $id_categoria = $_POST['id_categoria'];

    $imagem = '';
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $imagem = 'uploads/' . uniqid() . '_' . $_FILES['imagem']['name'];
        move_uploaded_file($_FILES['imagem']['tmp_name'], $imagem);
    }

    $stmt = $pdo->prepare("INSERT INTO veiculos (modelo, marca, ano, placa, cor, id_categoria, imagem) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$modelo, $marca, $ano, $placa, $cor, $id_categoria, $imagem]);

    header("Location: index.php");

    
}

$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll();

$veiculos = $pdo->query("
    SELECT v.*, c.nome AS categoria 
    FROM veiculos v 
    LEFT JOIN categorias c ON v.id_categoria = c.id
    ORDER BY v.id DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cadastrar Veículo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4">Cadastro de Veículo</h1>
    <form method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
        <div class="col-md-6">
            <label for="modelo" class="form-label">Modelo</label>
            <input type="text" class="form-control" id="modelo" name="modelo" placeholder="Modelo" required>
            <div class="invalid-feedback">Por favor, informe o modelo.</div>
        </div>

        <div class="col-md-6">
            <label for="marca" class="form-label">Marca</label>
            <input type="text" class="form-control" id="marca" name="marca" placeholder="Marca" required>
            <div class="invalid-feedback">Por favor, informe a marca.</div>
        </div>

        <div class="col-md-4">
            <label for="ano" class="form-label">Ano</label>
            <input type="number" class="form-control" id="ano" name="ano" placeholder="Ano" required min="1900" max="2100">
            <div class="invalid-feedback">Por favor, informe um ano válido.</div>
        </div>

        <div class="col-md-4">
            <label for="placa" class="form-label">Placa</label>
            <input type="text" class="form-control" id="placa" name="placa" placeholder="Placa" required>
            <div class="invalid-feedback">Por favor, informe a placa.</div>
        </div>

        <div class="col-md-4">
            <label for="cor" class="form-label">Cor</label>
            <input type="text" class="form-control" id="cor" name="cor" placeholder="Cor" required>
            <div class="invalid-feedback">Por favor, informe a cor.</div>
        </div>

        <div class="col-md-6">
            <label for="id_categoria" class="form-label">Categoria</label>
            <select class="form-select" id="id_categoria" name="id_categoria" required>
                <option value="" selected disabled>Escolha uma categoria</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Por favor, selecione uma categoria.</div>
        </div>

        <div class="col-md-6">
            <label for="imagem" class="form-label">Imagem do Veículo</label>
            <input class="form-control custom-placeholder" type="file" id="imagem" name="imagem" required accept="image/*">
            <div class="invalid-feedback">Por favor, envie uma imagem.</div>
        </div>

        <div class="col-12">
            <button class="btn btn-primary" type="submit">Cadastrar Veículo</button>
            <a href="index.php" class="btn btn-secondary ms-2">Cancelar</a>
        </div>
    </form>
    <?php if ($veiculos): ?>
    <h2 class="mt-5">Veículos Cadastrados</h2>
    <div class="table-responsive">
        <table class="table table-dark table-striped table-bordered align-middle mt-3">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Ano</th>
                    <th>Placa</th>
                    <th>Cor</th>
                    <th>Categoria</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($veiculos as $v): ?>
                    <tr>
                        <td>
                            <?php if ($v['imagem']): ?>
                                <img src="<?= htmlspecialchars($v['imagem']) ?>" alt="Imagem" width="100">
                            <?php else: ?>
                                Sem imagem
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($v['modelo']) ?></td>
                        <td><?= htmlspecialchars($v['marca']) ?></td>
                        <td><?= htmlspecialchars($v['ano']) ?></td>
                        <td><?= htmlspecialchars($v['placa']) ?></td>
                        <td><?= htmlspecialchars($v['cor']) ?></td>
                        <td><?= htmlspecialchars($v['categoria']) ?></td>
                        <td>
                            <a href="editar_veiculo.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="excluir_veiculo.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este veículo?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="mt-5">Nenhum veículo cadastrado.</p>
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
