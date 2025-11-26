<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'con.php';

header('Content-Type: application/json');

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    try {
        $conexao = new Conexao();
        $conn = $conexao->conn;

        $stmt = $conn->prepare("SELECT nome, email FROM alunos");
        $stmt->execute();
        $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($alunos) {
            $response = [
                'success' => true,
                'res' => $alunos
            ];
        } else {
            $response = [
                'success' => false,
                'message' => "Nenhum aluno encontrado"
            ];
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
