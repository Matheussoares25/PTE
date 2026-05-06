<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("../config/conn.php");
include("auth.php");

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $sql = $pdo->prepare("SELECT 
	    u.nome,
	    u.Foto,
	    SUM(up.nota) AS total_notas
	FROM use_prova up
	INNER JOIN usuarios u ON u.id = up.id_user
	GROUP BY u.id, u.nome
	ORDER BY total_notas DESC;");

    $sql->execute();
    $qntAll = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($qntAll as &$u) {
        $u['Foto'] = "data:image/jpeg;base64," . base64_encode($u['Foto']);
    }

    echo json_encode(["qnt" => $qntAll]);


} catch (Exception $e) {
    echo $e->getMessage();
}
