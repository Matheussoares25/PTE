<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

include("conn.php");
include("authADM.php");

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $id_usuario = $_POST["iduser"] ?? '';
    $id_vaga = $_POST["idvaga"] ?? '';
   

    $sqlV = "SELECT * FROM candidaturas WHERE id_user = :id_usuario AND id_vaga = :id_vaga";
    $stmt = $pdo->prepare($sqlV);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->bindParam(':id_vaga', $id_vaga);
    $stmt->execute();
    $candidaturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($candidaturas) > 0) {
        echo json_encode(['Existe' => true]);
        exit;
    }

    $sqlInsert = "INSERT INTO candidaturas (id_user,id_vaga,data_cand) VALUES (:id_usuario, :id_vaga, now())"; 
    $stmt = $pdo->prepare($sqlInsert);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->bindParam(':id_vaga', $id_vaga);
    $stmt->execute();

    echo json_encode([ "sucesso" => true]);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}