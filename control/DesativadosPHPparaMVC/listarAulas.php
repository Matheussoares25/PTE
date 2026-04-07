<?php
header('Content-Type: application/json');

include "conn.php";
include "auth.php";

$id_modulo = $_POST["idModulo"] ?? 0;


if (!$id_modulo) {
    echo json_encode([
        "sucesso" => false,
        "msg" => "ID do módulo não informado"
    ]);
    exit;
}

try {
    $con = new Conexao();
    $pdo = $con->conn;

    $sql = $pdo->prepare("SELECT * FROM aulas WHERE id_modulo = :id_modulo
    and excluido = 0");
    $sql->bindValue(":id_modulo", $id_modulo);
    $sql->execute();

    $aulas = $sql->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["sucesso" => true,"aulas"   => $aulas]);

} catch (Exception $e) {
    echo json_encode([
        "sucesso" => false,
        "msg" => "Erro: " . $e->getMessage()
    ]);
}
