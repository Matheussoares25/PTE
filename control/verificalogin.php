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


        $stmt = $pdo->prepare("SELECT id, senha, tipo, acess 
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


        $_SESSION['id'] = $res['id'];
        $_SESSION['tipo'] = (int) $res['tipo'];


        $token = bin2hex(random_bytes(32));

        $stmtUpdate = $pdo->prepare("UPDATE usuarios SET token = :token WHERE id = :id");
        $stmtUpdate->bindParam(':token', $token);
        $stmtUpdate->bindParam(':id', $res['id']);
        $stmtUpdate->execute();


        $stmtAcess = $pdo->prepare("UPDATE usuarios SET acess = 1 WHERE id = :id AND acess = 0");
        $stmtAcess->bindParam(':id', $res['id']);
        $stmtAcess->execute();

        $dados = [
            'success' => true,
            'token' => $token,
            'id' => $res['id'],
            'tipo' => $res['tipo']
        ];

        if ($res['acess'] == 0) {
            $dados['PACESS'] = true;
            $stmtAcess = $pdo->prepare("UPDATE usuarios SET acess = 1 WHERE id = :id AND acess = 0");
            $stmtAcess->bindParam(':id', $res['id']);
            $stmtAcess->execute();


        }

        echo json_encode($dados);


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