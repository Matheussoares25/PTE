<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

include("../config/conn.php");
include("authADM.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $con = new Conexao();
        $pdo = $con->conn;

        $email = $_SESSION['email'];
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
                'naoexiste' => true,
                'message' => 'Usuário não encontrado.'
            ]);
            exit;
        }

        if (!password_verify($senha, $res['senha'])) {
            echo json_encode(['serrada' => true]);
            exit;
        }

        if($res['tipo'] != 2) {
            echo json_encode(['negado'=> true]);
        }else{
            echo json_encode(['liberado'=> true]);
        }

        

    } catch (PDOException $e) {
        echo "Erro na conexão: " . $e->getMessage();
    }
}