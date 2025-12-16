<?php
header("Content-Type: application/json");
include "conn.php";
include "authADM.php";


try {

    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $qtd = intval($_POST['qtd'] ?? 0);
    $nome = $_POST['nome'] ?? '';
    $idCurso = $_POST['idCurso'] ?? null;

    $sqlNomeMod = $pdo->prepare("INSERT INTO modulos (nome_modolu, id_curso) values (:nomeCurso, :idCurso)");
    $sqlNomeMod->bindParam(':idCurso', $idCurso);
    $sqlNomeMod->bindParam(':nomeCurso', $nome);
    $sqlNomeMod->execute();


    $id_modulo = $pdo->lastInsertId();

    if ($qtd > 0) {
        $sql = $pdo->prepare(
            "INSERT INTO Aulas (id_modulo, excluido) VALUES (:id_modulo, 0)"
        );

        for ($i = 1; $i <= $qtd; $i++) {
            $sql->execute([
                ':id_modulo' => $id_modulo
            ]);
        }
    }

    echo json_encode(['sucesso' => true]);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>