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

    $sql = $pdo->prepare("SELECT * FROM vagas");
    $sql->execute();
    $vagas = $sql->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($vagas);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
?>