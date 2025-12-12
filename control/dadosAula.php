<?php
header("Content-Type: application/json");
include "conn.php";
include "authADM.php";

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $id = $_POST["id"] ?? 0;


    $sql = $pdo->prepare("SELECT * FROM Midias WHERE id_aula = :id");
    $sql->execute([":id" => $id]);

    $midia = $sql->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "sucesso" => true,
        "dados" => $midia
    ]);

} catch (Exception $e) {
    echo json_encode([
        "sucesso" => false,
        "erro" => $e->getMessage()
    ]);
}
