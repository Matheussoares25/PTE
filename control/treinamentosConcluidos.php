<?php
header('Content-Type: application/json; charset=utf-8');
include("conn.php");

try{
    $idUser = $_POST['id'] ?? '';
    
    $conexao = new Conexao();
    $pdo = $conexao->conn;


    $querySelect = $pdo->prepare("SELECT id, nome FROM treinamentos WHERE status = 2");
    $querySelect->execute();    

    $treinamentos = $querySelect->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($treinamentos);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}

