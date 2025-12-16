<?php
header("Content-Type: application/json");
include "conn.php";
include "authADM.php";

try {

    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $idModulo = $_POST["idModulo"] ?? 0;

    $sql = $pdo->prepare("INSERT INTO Aulas (id_modulo, excluido) VALUES (:id_modulo, 0)");
    $sql->bindParam(":id_modulo", $idModulo);
    $sql->execute();

    echo json_encode(["sucesso" => true]);

} catch (Exception $e) {
    echo json_encode(["sucesso" => false, "erro" => $e->getMessage()]);
}
?>