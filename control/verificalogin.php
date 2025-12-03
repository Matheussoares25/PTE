<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); 
session_regenerate_id(true);

include("conn.php");

header('Content-Type: application/json; charset=utf-8');

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $con = new Conexao();
        $pdo = $con->conn;

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

       
        $stmt = $pdo->prepare("SELECT id, senha, tipo 
            FROM usuarios 
            WHERE email = :email
        ");
        $stmt->bindParam(':email', $email);
        $stmt->execute();   
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$res) {
            echo json_encode([
                'NAOEXISTE' => true,
                'message' => 'Usuário não encontrado.'
            ]);
            exit;
        }

        if (!password_verify($senha, $res['senha'])) {
            echo json_encode(['serrada' => true]);
            exit;
        }

        // 🔥 INICIA A SESSION DO USUÁRIO
        $_SESSION['id'] = $res['id'];
        $_SESSION['tipo'] = $res['tipo']; // comum / admin

        // Continua usando o token se quiser
        $token = bin2hex(random_bytes(32)); 

        $stmtUpdate = $pdo->prepare("
            UPDATE usuarios SET token = :token WHERE id = :id
        ");
        $stmtUpdate->bindParam(':token', $token);
        $stmtUpdate->bindParam(':id', $res['id']);
        $stmtUpdate->execute();

        echo json_encode([
            'success' => true,
            'token' => $token,
            'id'    => $res['id'],
            'tipo'  => $res['tipo'] // 🔥 retorna ao front
        ]);
        exit;

    } catch (Exception $e) {

        echo json_encode([
            'success' => false,
            'message' => 'Erro inesperado no servidor.',
            'error' => $e->getMessage()
        ]);
        exit;
    }
}

?>
