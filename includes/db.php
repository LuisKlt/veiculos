<?php
try {
    $pdo = new PDO('mysql:host=localhost', 'root', '1234');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS veiculos_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    $pdo = new PDO('mysql:host=localhost;dbname=veiculos_db', 'root', '1234');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE TABLE IF NOT EXISTS categorias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100),
        email VARCHAR(100),
        senha VARCHAR(255)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS veiculos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        placa VARCHAR(20) NOT NULL,
        cor VARCHAR(50),
        modelo VARCHAR(100),
        marca VARCHAR(100),
        ano INT,
        id_categoria INT,
        imagem VARCHAR(255),
        FOREIGN KEY (id_categoria) REFERENCES categorias(id) ON DELETE SET NULL
    )");

} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>
