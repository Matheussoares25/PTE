<?php

header('Content-Type: application/json; charset=utf-8');

include("conn.php");

try{
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $idUser = $_POST['idUser'] ?? '';
  

    $stmt = $pdo->prepare("UPDATE usuarios SET token = NULL WHERE id = :id");
    $stmt->bindParam(':id', $idUser);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}