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

    $sql = $pdo->prepare("SELECT * FROM use_prova where id_user = :id and id_prova = :idProva");
    $sql->bindParam(":id", $_SESSION["id"]);
    $sql->bindParam(":idProva", $idProva);
    $sql->execute();
    $provas = $sql->fetchAll(PDO::FETCH_ASSOC);

    $qtdProvas = count($provas);


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


   

    if ($porcentagem >= 0) {

        $sql = $pdo->prepare(" UPDATE use_prova SET id_prova = :id_prova,acertos = :acertos,data_conclusao = NOW(),id_user = :id_usuario,aprovado = 0, porcentagem = :porcentagem, qtd_questoes = :qtdquestoes
        WHERE id_user = :id_usuario and id_prova = :id_prova");
        $sql->bindParam(":id_prova", $idProva);
        $sql->bindParam(":acertos", $acertos);
        $sql->bindParam(":porcentagem", $porcentagem);
        $sql->bindParam(":qtdquestoes", $total);
        $sql->bindParam(":id_usuario", $_SESSION["id"]);
        $sql->execute();

        echo json_encode([
            "sucesso" => true,
            "acertos" => $acertos,
            "porcentagem" => $porcentagem,
            "qtdProvas" => $qtdProvas
        ]);

    } else if ($porcentagem >= 75) {
        $sql = $pdo->prepare(" UPDATE use_prova SET id_prova = :id_prova,acertos = :acertos,data_conclusao = NOW(),id_user = :id_usuario,aprovado = 1, porcentagem = :porcentagem, qtd_questoes = :qtdquestoes
        WHERE id_user = :id_usuario and id_prova = :id_prova");
        $sql->bindParam(":id_prova", $idProva);
        $sql->bindParam(":acertos", $acertos);
        $sql->bindParam(":porcentagem", $porcentagem);
        $sql->bindParam(":qtdquestoes", $total);
        $sql->bindParam(":id_usuario", $_SESSION["id"]);
        $sql->execute();

        echo json_encode([
            "sucesso" => true,
            "acertos" => $acertos,
            "porcentagem" => $porcentagem,
            "qtdProvas" => $qtdProvas
        ]);


    }
    else {

        echo json_encode([
            "sucesso" => false,
            "reprova" => true,
            "acertos" => $acertos,
            "porcentagem" => $porcentagem
            
        ]);
    }

    //VERIFICAÇÂO SE JA FEZ A PROVA ANTES





} catch (Exception $e) {

    echo json_encode([
        "sucesso" => false,
        "erro" => $e->getMessage()
    ]);
}
