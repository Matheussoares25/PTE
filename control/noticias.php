<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

include("conn.php");

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $querySelect = $pdo->prepare("SELECT id, titulo, conteudo,data_noticia FROM noticias ");
    $querySelect->execute();

    $noticias = $querySelect->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($noticias);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
