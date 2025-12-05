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

    $verifica = $pdo->prepare("SELECT * FROM use_treinamentos WHERE id_usuario = :iduser AND id_curso = :idcurso LIMIT 1");
    $verifica->bindParam(":iduser", $iduser);
    $verifica->bindParam(":idcurso", $idcurso);
    $verifica->execute();

    $registro = $verifica->fetch(PDO::FETCH_ASSOC); 

    if ($registro) {
        echo json_encode([
            "EXISTE" => true,
        ]);
        exit;
    }


    $sql = $pdo->prepare("INSERT INTO use_treinamentos (id_usuario, id_curso, status_curso)  VALUES (:iduser, :idcurso, 1)");
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
