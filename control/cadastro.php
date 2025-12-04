<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("conn.php");

header('Content-Type: application/json; charset=utf-8');

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $conexao = new Conexao();
        $pdo = $conexao->conn;

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $foto  = $_FILES['foto'] ?? null;

       
        if (empty($email) || empty($senha)) {
            echo json_encode(["success" => false, "mensagem" => "Email e senha obrigatórios"]);
            exit;
        }

        if (!$foto || $foto['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(["success" => false, "mensagem" => "Foto é obrigatória"]);
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        if ($stmt->fetch()) {
            echo json_encode(["Existe" => true]);
            exit;
        }
        
        
        $conteudo = file_get_contents($foto['tmp_name']);

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

       
        $queryInsert = $pdo->prepare("INSERT INTO usuarios (Email, senha, ativos, Foto, tipo) VALUES (:email, :senha, 1, :foto, 2)");
        $queryInsert->bindValue(':email', $email);
        $queryInsert->bindValue(':senha', $senhaHash);
        $queryInsert->bindValue(':foto', $conteudo, PDO::PARAM_LOB);

        if ($queryInsert->execute()) {
            $response = ['success' => true];
        } else {
            $erro = $queryInsert->errorInfo();
            $response = ['success' => false, 'erro' => $erro[2]];
        }

    } catch (PDOException $e) {
        $response = [
            'success' => false,
            'erro' => $e->getMessage()
        ];
    }
}

echo json_encode($response);
?>
