<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");


include "conn.php";
include("auth.php"); 

try {
    $idUser = $_POST['id'] ?? '';

    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $sql = $pdo->prepare("SELECT id, nome FROM treinamentos WHERE status = 1");
    $sql->execute();

    $treinamentos = $sql->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
echo json_encode($treinamentos);
?>