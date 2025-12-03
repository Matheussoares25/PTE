<?php
header('Content-Type: application/json; charset=UTF-8');
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['valido' => false]);
    exit;
}
try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;
    $token = $_POST['token'] ?? '';

    if (!$token) {
        echo json_encode(['valido' => false]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo json_encode(['valido' => false]);
    exit;
}

echo json_encode(['valido' => $usuario ? true : false]);
exit;
?>