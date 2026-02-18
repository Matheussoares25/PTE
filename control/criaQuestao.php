<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");


include "conn.php";
include("authADM.php");

try{
    $conexao = new Conexao();
    $pdo = $conexao->conn;

     $pdo->beginTransaction();

    $idAula = $_POST["idAula"] ?? 0;
    $pergunta = $_POST["pergunta"] ?? "";
    $alt1 = $_POST["alt1"] ?? "";
    $alt2 = $_POST["alt2"] ?? "";
    $alt3 = $_POST["alt3"] ?? "";
    $alt4 = $_POST["alt4"] ?? "";
    $correta = $_POST["correta"] ?? "";

   

    $sql = $pdo->prepare("INSERT INTO questoes (id_prova,pergunta) VALUES (:id_prova, :pergunta)");
    $sql->bindParam(":id_prova", $idAula);
    $sql->bindParam(":pergunta", $pergunta);
    $sql->execute();

    $idQuestao = $pdo->lastInsertId();

     $sqlAlt = $pdo->prepare("INSERT INTO alternativas (id_questao, texto, correta)VALUES (:id_questao, :texto, :correta)
");

$alternativas = [$alt1, $alt2, $alt3, $alt4];

foreach ($alternativas as $index => $alt) {

    $altCorreta = ($correta == ($index + 1)) ? 1 : 0;

    $sqlAlt->execute([
        ":id_questao" => $idQuestao,
        ":texto" => $alt,
        ":correta" => $altCorreta
    ]);
}

    $pdo->commit();
    
    echo json_encode(["sucesso" => true]);

}catch(Exception $e){
    echo json_encode(["sucesso" => false, "erro" => $e->getMessage()]);
}

?>