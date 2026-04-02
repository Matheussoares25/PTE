<?php
header("Content-Type: application/json");

include("conn.php");
include("authADM.php");

try{
    $con = new Conexao();
    $pdo = $con->conn;

    $idCurso = $_POST["idcurso"] ?? null;
    $iduser = $_POST["usuario"] ?? null;

   

    $sql = $pdo->prepare("INSERT INTO use_treinamentos (id_curso, id_usuario,status_curso,data_curso) VALUES (:idCurso, :iduser, 1, now())");
    $sql->bindParam(":idCurso", $idCurso);
    $sql->bindParam(":iduser", $iduser);
    $sql->execute();

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "erro" => $e->getMessage()]);
}