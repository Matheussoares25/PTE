<?php
header("Content-Type: application/json");
include "conn.php";
include "authADM.php";

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $id = $_POST["id"] ?? 0;

    $sql = $pdo->prepare("UPDATE Aulas SET excluido = 1 WHERE id = :id");
    $sql->bindParam(":id", $id);
    $sql->execute();

    echo json_encode(["sucesso" => true]);

} catch (Exception $e) {
    echo json_encode(["sucesso" => false, "erro" => $e->getMessage()]);
}