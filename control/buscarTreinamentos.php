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

    $sql = $pdo->prepare("SELECT a.id_usuario,a.id_curso,a.status_curso,c.nome  FROM use_treinamentos AS a 
INNER JOIN usuarios AS b ON a.id_usuario = b.id 
LEFT JOIN treinamentos AS c ON a.id_curso = c.id WHERE a.id_usuario = :idUser");
    $sql->bindParam("idUser", $idUser);
    $sql->execute();

    $treinamentos = $sql->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
echo json_encode($treinamentos);
?>