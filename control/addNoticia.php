<?php
date_default_timezone_set('America/Sao_Paulo');
include("conn.php");
include ("authADM.php");

$titulo = $_POST['titulo'] ?? '';
$conteudo = $_POST['conteudo'] ?? '';
$data_noticia = date('Y-m-d H:i:s');

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;
    $sql = $pdo->prepare("INSERT INTO noticias (titulo, conteudo, data_noticia) VALUES (:titulo, :conteudo, :data_noticia)");
    $sql->bindParam(':titulo', $titulo);
    $sql->bindParam(':conteudo', $conteudo);
    $sql->bindParam(':data_noticia', $data_noticia);
    $sql->execute();


   if($sql){
    echo json_encode(["sucesso" => true]);
   }
} catch (PDOException $e) {
    echo "Erro ao inserir dados: " . $e->getMessage();
}
