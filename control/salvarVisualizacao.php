<?php

include("../config/conn.php");
include("auth.php");

$con = new Conexao();
$pdo = $con->conn;

$idAula = $_POST["idAula"];
$idUser = $_POST["idUser"];



$sql = $pdo->prepare("
INSERT INTO progress (id_user, id_aula, assistido, data_assistida)
VALUES (:user, :aula, 1, NOW())
ON DUPLICATE KEY UPDATE data_assistida = NOW()
");
$sql->bindParam(":user", $idUser);
$sql->bindParam(":aula", $idAula);
$sql->execute();

echo json_encode(["sucesso" => true]);
