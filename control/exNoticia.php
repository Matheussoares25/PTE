<?php

include("conn.php");
include("authADM.php");

$id = $_POST['id'] ?? '';


try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;
    $sql = $pdo->prepare("DELETE FROM noticias WHERE id = :id");
    $sql->bindParam(':id', $id);
    $sql->execute();
    
    
    if($sql){
        echo json_encode(["sucesso" => true]);
    }
} catch (PDOException $e) {
    echo "Erro ao inserir dados: " . $e->getMessage();
}
?>