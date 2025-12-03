<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'con.php';

header('Content-Type: application/json');

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $nome = $_POST['nome'];
        $email = $_POST['email'];

        $conexao = new Conexao();
        $conn = $conexao->conn;

        $stmt = $conn->prepare("UPDATE alunos SET nome = :nome, email = :email WHERE email = :email");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $res = $stmt->execute();

        if ($res) {
            $response = ['success' => true];
        } else {
            $response = ['sucesses' => false];
        }
    } catch (PDOException $e) {
        $response = [
            'success' => false,
            'message' => "Erro no banco"
        ];
    }
    echo json_encode($response);
    exit;
}


?>