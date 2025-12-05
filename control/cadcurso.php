<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

include("conn.php");
include("authADM.php");

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $iduser = $_POST["usuario"] ?? '';
    $idcurso = $_POST['idcurso'] ?? '';

    $sql = $pdo->prepare("INSERT INTO use_treinamentos 
        (id_usuario, id_curso, status_curso) 
        VALUES (:iduser, :idcurso, 1)");

    $sql->bindParam(":iduser", $iduser);
    $sql->bindParam(":idcurso", $idcurso);

    $executou = $sql->execute();

    if ($executou) {
        echo json_encode(["sucesso" => true]);
    } else {
        echo json_encode([
            "sucesso" => false, 
            "erro" => "Falha ao inserir no banco"
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "sucesso" => false, 
        "erro" => $e->getMessage()
    ]);
}
