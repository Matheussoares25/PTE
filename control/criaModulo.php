<?php
header("Content-Type: application/json");
include "conn.php";
include "authADM.php";

try {

    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $qtd = intval($_POST['qtd'] ?? 0);
    $nome = $_POST['nome'] ?? '';
    $id_modulo = $_POST['idModulo'] ?? null;
    $idCurso = $_POST['idCurso'] ?? null;



   

    $sqlNomeMod = $pdo->prepare("UPDATE Modulos set nome_modolu = :nome WHERE id = :id_modulo");
    $sqlNomeMod->bindParam(':id_modulo', $id_modulo);
    $sqlNomeMod->bindParam(':nome', $nome);
    $sqlNomeMod->execute();


    if ($qtd) {
        $sql = $pdo->prepare(
            "INSERT INTO Aulas (id_modulo) VALUES (:id_modulo)"
        );

        for ($i = 1; $i <= $qtd; $i++) {

            $sql->execute([
                ':id_modulo' => $id_modulo,

            ]);
        }
    }

    echo json_encode(['sucesso' => true]);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>