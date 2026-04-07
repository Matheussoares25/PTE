<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "conn.php";
include "auth.php";

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $idUser = $_POST["iduser"];
    $curso = $_POST["idCurso"];

    $sql = $pdo->prepare("
        SELECT 
            up.porcentagem,
            u.nome AS nome_usuario,
            t.nome AS nome_curso
        FROM use_prova up
        INNER JOIN aulas a ON up.id_prova = a.id
        INNER JOIN modulos m ON a.id_modulo = m.id
        INNER JOIN treinamentos t ON t.id = m.id_curso
        INNER JOIN usuarios u ON u.id = up.id_user
        WHERE up.id_user = :idUser
          AND t.id = :idCurso
          AND a.tipo = 2
    ");
    $sql->bindParam(":idUser", $idUser);
    $sql->bindParam(":idCurso", $curso);
    $sql->execute();

    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    $porcentagens = array_column($result, 'porcentagem');
    $nomeUsuario = $result[0]['nome_usuario'] ?? '';
    $nomeCurso = $result[0]['nome_curso'] ?? '';
    $totalFeitas = count($porcentagens);
    $media = $totalFeitas > 0 ? round(array_sum($porcentagens) / $totalFeitas, 2) : 0;

    $sql1 = $pdo->prepare("
        SELECT COUNT(a.id) AS total_provas_curso
        FROM modulos m
        INNER JOIN aulas a ON a.id_modulo = m.id
        WHERE m.id_curso = :idcurso
          AND a.tipo = 2
    ");
    $sql1->bindParam(":idcurso", $curso);
    $sql1->execute();
    $data1 = $sql1->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "nome_usuario" => $nomeUsuario,
        "nome_curso" => $nomeCurso,
        "porcentagens" => $porcentagens,
        "media_porcentagem" => $media,
        "total_provas_feitas" => $totalFeitas,
        "total_provas_curso" => $data1["total_provas_curso"] ?? 0
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "erro" => $e->getMessage()
    ]);
}