<?php
header("Content-Type: application/json");
include "conn.php";
include "auth.php";

try {

    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $idProva = $_POST["idProva"] ?? 0;
    $respostas = json_decode($_POST["respostas"], true);

    $acertos = 0;

    foreach ($respostas as $resp) {

        $alternativa = $resp["alternativa_id"] ?? null;

        if (!$alternativa) {
            continue;
        }

        $sql = $pdo->prepare("SELECT correta FROM alternativas WHERE id = :id");
        $sql->bindParam(":id", $alternativa);
        $sql->execute();

        $alt = $sql->fetch(PDO::FETCH_ASSOC);

        if ($alt && $alt["correta"] == 1) {
            $acertos++;
        }
    }

    
    $sql = $pdo->prepare("
        SELECT COUNT(*) as total 
        FROM alternativas 
        WHERE id_prova = :idProva AND correta = 1
    ");

    $sql->bindParam(":idProva", $idProva);
    $sql->execute();

    $total = $sql->fetch(PDO::FETCH_ASSOC)["total"];

    $porcentagem = ($acertos * 100) / $total;
    $data = date('Y-m-d H:i:s');

    if ($porcentagem >= 75) {

        $sql = $pdo->prepare(" INSERT INTO use_prova (id_prova, acertos,data_conclusao,id_user,aprovado)
            VALUES (:id_prova, :acertos,:data_conclusao, :id_usuario, 1)
        ");

        $sql->bindParam(":id_prova", $idProva);
        $sql->bindParam(":acertos", $acertos);
        $sql->bindParam(":id_usuario", $_SESSION["id"]);
        $sql->bindParam(":data_conclusao", $data);
        $sql->execute();

        echo json_encode([ "sucesso" => true,"acertos" => $acertos,"porcentagem" => $porcentagem
        ]);

    } else {

        echo json_encode([
            "sucesso" => false,
            "reprova" => true,
            "acertos" => $acertos,
            "porcentagem" => $porcentagem
        ]);
    }

} catch (Exception $e) {

    echo json_encode([
        "sucesso" => false,
        "erro" => $e->getMessage()
    ]);
}
