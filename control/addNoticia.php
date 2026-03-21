<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Sao_Paulo');
include("conn.php");
include ("authADM.php");

$titulo = $_POST['titulo'] ?? '';
$conteudo = $_POST['conteudo'] ?? '';
$vaga = $_POST['vaga'] ?? '';
$data_noticia = date('Y-m-d H:i:s');


if($vaga == 1){
    $conexao = new Conexao();
    $pdo = $conexao->conn;
    $sql = $pdo->prepare("INSERT INTO vagas (titulo, conteudo, data_vaga) VALUES (:titulo, :conteudo, now())");
    $sql->bindParam(':titulo', $titulo);
    $sql->bindParam(':conteudo', $conteudo);
    $sql->execute();
   

}


if($titulo == "" || $conteudo == null){
    echo json_encode(["vazio" => true]);
    return;
}

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;
    $sql = $pdo->prepare("INSERT INTO noticias (titulo, conteudo, data_noticia, vaga) VALUES (:titulo, :conteudo, :data_noticia,:vaga)");
    $sql->bindParam(':titulo', $titulo);
    $sql->bindParam(':conteudo', $conteudo);
    $sql->bindParam(':data_noticia', $data_noticia);
    $sql->bindParam(':vaga', $vaga);
    $sql->execute();


   if($sql){
    echo json_encode(["sucesso" => true]);
   }
} catch (PDOException $e) {
    echo "Erro ao inserir dados: " . $e->getMessage();
}
