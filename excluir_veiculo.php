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

// Buscar imagem para excluir o arquivo físico também, se desejar
$stmt = $pdo->prepare("SELECT imagem FROM veiculos WHERE id = ?");
$stmt->execute([$id]);
$veiculo = $stmt->fetch();

if ($veiculo && !empty($veiculo['imagem']) && file_exists($veiculo['imagem'])) {
    unlink($veiculo['imagem']); // Apaga o arquivo de imagem
}

// Excluir do banco
$stmt = $pdo->prepare("DELETE FROM veiculos WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit;
