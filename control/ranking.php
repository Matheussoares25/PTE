<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("conn.php");
include("auth.php");

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $sql = $pdo->prepare("SELECT u.id AS id_usuario,u.nome AS nome_usuario,u.email,u.Foto,
        COUNT(t.id_curso) AS total_cursos
        FROM usuarios u INNER JOIN use_treinamentos t 
        ON u.id = t.id_usuario
        AND t.status_curso = 2
        GROUP BY u.id, u.nome, u.email,u.Foto
        ORDER BY total_cursos DESC
    ");

    $sql->execute();
    $qntAll = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($qntAll as &$u) {
        $u['Foto'] = "data:image/jpeg;base64," . base64_encode($u['Foto']);
    }

    echo json_encode(["qnt" => $qntAll]);


} catch (Exception $e) {
    echo $e->getMessage();
}
