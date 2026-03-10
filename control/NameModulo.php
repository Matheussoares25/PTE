<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

include("conn.php");
include("authADM.php");

try{
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $nome = $_POST["nome"] ?? '';
    $idModulo = $_POST["idModulo"] ?? '';

  
    
    $sqlV = "SELECT * FROM modulos where nome_modolu = :nome";
    $stmt = $pdo->prepare($sqlV);
    $stmt->bindParam(':nome', $nome);
    $stmt->execute();
    $treinamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($treinamentos) > 0) {
        echo json_encode(['Existe' => true]);
        exit;
    }

    $sqlInsert = "UPDATE modulos set nome_modolu = :nome where id = :idModulo";
    $stmt = $pdo->prepare($sqlInsert);
    $stmt->bindValue(':idModulo', $idModulo);
    $stmt->bindValue(':nome', $nome);
    $stmt->execute();
    

  
  

    echo json_encode(['success' => true,'message' => 'Módulo atualizado com sucesso']);
} catch (PDOException $e) {
    echo json_encode(['sucesso' => false, 'msg' => $e->getMessage()]);

}
?>