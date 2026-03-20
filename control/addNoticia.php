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
//CRIAR NO BANCO DE DADOS A COLUNA VAGA COMO TINYT PARA INSERIR O VALOR DA SELEÇÂO;
//CRIAR O BOTAO NA HORA DE LISTAR AS NOTICIAS E VERIFICAR SE ESTA COMO 1 A COLUNA VAGA, SE SIM BOTAO , SE NAO, SEM BOTAO;
//BOTAO LEVA PARA UM FORMULARIO DE INTERESSE NA VAGA;
$data_noticia = date('Y-m-d H:i:s');

if($titulo == "" || $conteudo == null){
    echo json_encode(["vazio" => true]);
    return;
}

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
