<?php
require_once 'includes/db.php';

$id_categoria = $_GET['id'];
$veiculos = $pdo->prepare("SELECT * FROM veiculos WHERE id_categoria = ?");
$veiculos->execute([$id_categoria]);
$veiculos = $veiculos->fetchAll();
?>

<h1>Veículos da Categoria</h1>
<?php foreach ($veiculos as $v): ?>
    <div>
        <h3><a href="veiculo.php?id=<?= $v['id'] ?>"><?= $v['modelo'] ?></a></h3>
        <p><?= $v['marca'] ?> - <?= $v['ano'] ?></p>
        <img src="<?= $v['imagem'] ?>" width="200">
    </div>
<?php endforeach; ?>
