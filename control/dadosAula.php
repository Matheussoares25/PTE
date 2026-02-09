<?php
header("Content-Type: application/json");
ini_set('memory_limit', '512M');

include "conn.php";
include "auth.php";

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $id = $_POST["idAula"] ?? 0;

    $sql = $pdo->prepare("SELECT desc_midia, caminho_video FROM midias WHERE id_aula = :id");
    $sql->bindParam(":id", $id);
    $sql->execute();

    $midia = $sql->fetch(PDO::FETCH_ASSOC);

    if ($midia && $midia["caminho_video"]) {
        echo json_encode([
            "sucesso" => true,
            "dados" => [
                "desc_midia" => $midia['desc_midia'],
                "video" => $midia["caminho_video"]
            ]
        ]);
    } else {
        echo json_encode(["sucesso" => false, "msg" => "Nenhuma mídia encontrada"]);
    }

} catch (Exception $e) {
    echo json_encode([
        "sucesso" => false,
        "erro" => $e->getMessage()
    ]);
}
