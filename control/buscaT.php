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

    $idTreinamento = $_POST['id'] ?? '';

    $sql = $pdo->prepare("SELECT * FROM treinamentos where id = :id");
    $sql->bindParam("id", $idTreinamento);
    $sql->execute();
    $treinamentos = $sql->fetchAll(PDO::FETCH_ASSOC);


    $sql1 = $pdo->prepare("SELECT a.id_usuario,a.id_curso,a.status_curso,c.nome, b.email,a.matricula,a.data_curso  FROM use_treinamentos AS a 
INNER JOIN usuarios AS b ON a.id_usuario = b.id 
LEFT JOIN treinamentos AS c ON c.id = a.id_curso WHERE a.id_curso = :id");
    $sql1->bindParam("id", $idTreinamento);
    $sql1->execute();
    $treinamentos2 = $sql1->fetchAll(PDO::FETCH_ASSOC);

    
    

    $sql2 = $pdo->prepare("SELECT email,id FROM usuarios where ativos = 1 and tipo = 1");
    $sql2->execute();
    $usuarios = $sql2->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo $e->getMessage();
}
echo json_encode(["treinamentos" => $treinamentos, "relacionados" => $treinamentos2, "usuarios" => $usuarios]);


?>