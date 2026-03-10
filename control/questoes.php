<?php
header("Content-Type: application/json");


include "conn.php";
include "auth.php";

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $idProva = $_POST["idProva"] ?? "";
    



   $sql = $pdo->prepare("
SELECT 
    q.id AS id_questao,
    q.pergunta,
    a.id AS id_alternativa,
    a.texto
FROM questoes q
LEFT JOIN alternativas a 
    ON a.id_questao = q.id
WHERE q.id_prova = :idProva
ORDER BY q.id, a.id
");

$sql->bindValue(":idProva", $idProva);
$sql->execute();

$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

$questoes = [];

foreach ($resultado as $linha) {

    $id = $linha["id_questao"];

    if (!isset($questoes[$id])) {
        $questoes[$id] = [
            "id" => $id,
            "pergunta" => $linha["pergunta"],
            "alternativas" => []
        ];
    }

    if (!empty($linha["id_alternativa"])) {
        $questoes[$id]["alternativas"][] = [
            "id_alternativa" => $linha["id_alternativa"],
            "texto" => $linha["texto"]
        ];
    }
}

$questoes = array_values($questoes);

echo json_encode([
    "sucesso" => true,
    "Questoes" => $questoes,
    "idProva" => $idProva
]);


} catch (PDOException $e) {
    echo json_encode("Erro" . $e->getMessage());
}
?>