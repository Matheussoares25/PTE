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

        $stmt = $conn->prepare("INSERT INTO alunos (nome, email) VALUES (:nome, :email)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $res = $stmt->execute();


        if ($res) {
            $response = ['success' => true];

        } else {
            $response['message'] = 'Erro ao inserir usuário.';


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