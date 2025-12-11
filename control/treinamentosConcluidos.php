<?php
header('Content-Type: application/json; charset=utf-8');
include("conn.php");
include("auth.php"); 
try{
    $idUser = $_POST['id'] ?? '';

    $conexao = new Conexao();
    $pdo = $conexao->conn;


    $querySelect = $pdo->prepare("SELECT a.id_usuario,a.id_curso,a.status_curso,c.nome  FROM use_treinamentos AS a 
INNER JOIN usuarios AS b ON a.id_usuario = b.id 
LEFT JOIN treinamentos AS c ON a.id_curso = c.id WHERE a.id_usuario = :idUser AND a.status_curso = 1");
    $querySelect->bindParam("idUser", $idUser);
    $querySelect->execute();    

    $treinamentos = $querySelect->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($treinamentos);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}

