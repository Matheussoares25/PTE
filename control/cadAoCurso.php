<?php
header("Content-Type: application/json");

include("conn.php");
include("authADM.php");

try{
    $con = new Conexao();
    $pdo = $con->conn;

    $idCurso = $_POST["idcurso"] ?? null;
    $iduser = $_POST["usuario"] ?? null;

   

    $sql = $pdo->prepare("INSERT INTO use_treinamentos (id_curso, id_usuario) VALUES (:idCurso, :iduser)");
    $sql->bindParam(":idCurso", $idCurso);
    $sql->bindParam(":iduser", $iduser);
    $sql->execute();

    echo json_encode(["sucesso" => true]);

} catch (Exception $e) {
    echo json_encode(["sucesso" => false, "erro" => $e->getMessage()]);
}