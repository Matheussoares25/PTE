<?php
include("conn.php");
include("authADM.php"); 

$id = $_POST['id'] ?? '';
$titulo = $_POST['titulo'] ?? '';
$conteudo = $_POST['conteudo'] ?? '';

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $NotAtual = $pdo->prepare("SELECT * FROM noticias WHERE id = :id");
    $NotAtual->bindParam(':id', $id);
    $NotAtual->execute();
    $Noticia = $NotAtual->fetch(PDO::FETCH_ASSOC);


    $sql = $pdo->prepare("UPDATE noticias SET titulo = :titulo, conteudo = :conteudo WHERE id = :id");
    $sql->bindParam(':titulo', $titulo);
    $sql->bindParam(':conteudo', $conteudo);
    $sql->bindParam(':id', $id);
    $sql->execute();
    
    echo json_encode(["sucesso" => true, "Noticia" => $Noticia]);
} catch (PDOException $e) {
    echo "Erro ao inserir dados: " . $e->getMessage();
}

?>