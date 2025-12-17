<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

include("conn.php");
include("authADM.php");

try{
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $nome = $_POST["nome"] ?? '';
    $idcurso = $_POST["idCurso"] ?? '';
    
    $sqlV = "SELECT * FROM modulos where nome_modolu = :nome";
    $stmt = $pdo->prepare($sqlV);
    $stmt->bindParam(':nome', $nome);
    $stmt->execute();
    $treinamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($treinamentos) > 0) {
        echo json_encode(['Existe' => true]);
        exit;
    }

    $sqlInsert = "INSERT INTO modulos (id_curso) VALUES (:idcurso)";
    $stmt = $pdo->prepare($sqlInsert);
    $stmt->bindValue(':idcurso', $idcurso);
    $stmt->execute();
    $id = $pdo->lastInsertId();

  
    $sqlS = "SELECT * FROM modulos WHERE id = :id";
    $stmt = $pdo->prepare($sqlS);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $novoTreinamento = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['sucesso' => true, 'dados' => $novoTreinamento]);
} catch (PDOException $e) {
    echo json_encode(['sucesso' => false]);
}
?>