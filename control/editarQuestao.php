<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

include "../config/conn.php";
include("authADM.php");

try{
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $pdo->beginTransaction();

    $idAula   = $_POST["idAula"] ?? 0;
    $pergunta = $_POST["pergunta"] ?? "";
    $alt1     = $_POST["alt1"] ?? "";
    $alt2     = $_POST["alt2"] ?? "";
    $alt3     = $_POST["alt3"] ?? "";
    $alt4     = $_POST["alt4"] ?? "";
    $correta  = $_POST["correta"] ?? "";
    $idQuestao = $_POST["idQuestao"] ?? "";


    $sql = $pdo->prepare("UPDATE questoes SET pergunta = :pergunta WHERE id = :id");
    $sql->execute([
        ":pergunta" => $pergunta,
        ":id" => $idQuestao
    ]);

 
    $del = $pdo->prepare("DELETE FROM alternativas WHERE id_questao = :id");
    $del->execute([
        ":id" => $idQuestao
    ]);


    $sqlAlt = $pdo->prepare("
        INSERT INTO alternativas (id_questao, texto, correta, id_prova)
        VALUES (:id_questao, :texto, :correta, :id_aula)
    ");

    $alternativas = [$alt1, $alt2, $alt3, $alt4];

    foreach ($alternativas as $index => $alt) {

        $altCorreta = ($correta == ($index + 1)) ? 1 : 0;

        $sqlAlt->execute([
            ":id_questao" => $idQuestao,
            ":texto"      => $alt,
            ":correta"    => $altCorreta,
            ":id_aula"    => $idAula
        ]);
    }

    $pdo->commit();

    echo json_encode(["sucesso" => true]);

}catch(Exception $e){
    $pdo->rollBack();
    echo json_encode([
        "sucesso" => false,
        "erro" => $e->getMessage()
    ]);
}
?>