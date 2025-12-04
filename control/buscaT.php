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

    $idUser = $_POST['id'] ?? '';

    $sql = $pdo->prepare("SELECT * FROM treinamentos where id = :id");
    $sql->bindParam("id", $idUser);
    $sql->execute();

   $treinamentos = $sql->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    echo $e->getMessage();
}
echo json_encode((array) $treinamentos);
?>
