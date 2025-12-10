<?php
header("Content-Type: application/json");

include "conn.php";
include "auth.php";

try {
    $pdo = (new Conexao())->conn;

    $iduser = $_POST["id_usuario"] ?? null;
    $idcurso = $_POST["id_curso"] ?? null;

    $sql = $pdo->prepare("DELETE FROM use_treinamentos WHERE id_usuario = :u AND id_curso = :c");
    $sql->bindParam(":u", $iduser);
    $sql->bindParam(":c", $idcurso);
    $ok = $sql->execute();

    if ($ok) {
        echo json_encode(["sucesso" => true]);
    } else {
        echo json_encode(["sucesso" => false, "erro" => "Falha ao deletar"]);
    }

} catch (Exception $e) {
    echo json_encode(["sucesso" => false, "erro" => $e->getMessage()]);
}
?>