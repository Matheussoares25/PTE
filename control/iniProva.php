<?php
header("Content-Type: application/json");
include "../config/conn.php";
include "auth.php";

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $iduser = $_POST["iduser"] ?? null;
    $idprova = $_POST["idprova"] ?? null;

    $sql = $pdo->prepare("SELECT * FROM use_prova where id_user = :id and id_prova = :idProva");
    $sql->bindParam(":id",$iduser);
    $sql->bindParam(":idProva", $idprova);
    $sql->execute();
    $feita = $sql->fetchAll(PDO::FETCH_ASSOC);

    if (count($feita) > 0) {
        echo json_encode([
            "sucesso" => false,
            "Feita" => true,
        ]);
        return;
    }

   

    $sql = $pdo->prepare("INSERT INTO use_prova (id_prova, id_user, data_inicio) VALUES (:idprova, :iduser, NOW())");
    $sql->bindParam(":idprova", $idprova);
    $sql->bindParam(":iduser", $iduser);
    $sql->execute();

    echo json_encode(["sucesso" => true]);
} catch (Exception $e) {
    echo json_encode(["sucesso" => false, "erro" => $e->getMessage()]);


}
?>