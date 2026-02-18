<?php
header("Content-Type: application/json");
include "conn.php";
include "authADM.php";

try{
    $pdo = (new Conexao())->conn;

    $nomeP = $_POST["nomeP"];
    $idAula = $_POST["idAula"];
    $idModulo = $_POST["idModulo"];

    $sql = $pdo->prepare("UPDATE aulas set nome_aula = :nomeProva where id = :idProva and id_modulo = :idModulo");
    $sql -> execute([":nomeProva" => $nomeP, ":idProva" => $idAula, ":idModulo" => $idModulo]);



}
catch(PDOException $e){
    echo "". $e->getMessage();
}
?>