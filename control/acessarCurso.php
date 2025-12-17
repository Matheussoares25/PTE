<?php
session_start();
header('Content-Type: application/json');


if (!isset($_SESSION['id'])) {
    echo json_encode([
        "sucesso" => false,
        "msg" => "Usuário não autenticado"
    ]);
    exit;
}

$idUser = $_SESSION['id'];
$idCurso = $_POST['id'] ?? null;


if (!$idCurso) {
    echo json_encode([
        "sucesso" => false,
        "msg" => "Curso inválido"
    ]);
    exit;
}

include "conn.php";

try {


    $conexao = new Conexao();
    $pdo = $conexao->conn;


    $sql = $pdo->prepare("SELECT 
    m.id,
    m.nome_modolu,
    t.id   AS id_treinamento
    FROM modulos m
    INNER JOIN treinamentos t 
        ON m.id_curso = t.id
    WHERE t.id = ?
    ORDER BY m.id ASC
    LIMIT 1;
");
    $sql->execute([$idCurso]);
    $modulo = $sql->fetch(PDO::FETCH_ASSOC);
    $idModulo = $modulo['id'];

    $sql = $pdo->prepare("SELECT 
    id AS id_primeira_aula,
    nome_aula
FROM aulas
WHERE id_modulo = ?
ORDER BY id ASC
LIMIT 1;
");
    $sql->execute([$idModulo]);
    $aula = $sql->fetch(PDO::FETCH_ASSOC);
    $idAula = $aula['id_primeira_aula'];



    $sql = $pdo->prepare("SELECT id, id_modulo, id_aula, progresso 
    FROM on_treinamento
    WHERE id_user = ? AND id_curso = ?
");
    $sql->execute([$idUser, $idCurso]);
    $registro = $sql->fetch(PDO::FETCH_ASSOC);


    if (!$registro) {

        $sql = $pdo->prepare("INSERT INTO on_treinamento
        (id_user, id_curso, id_modulo,id_aula, status, progresso, data_ini)
        VALUES (?, ?, ?,?, 1, 0, NOW())
    ");
        $sql->execute([$idUser, $idCurso, $idModulo, $idAula]);

    } else {

        $sql = $pdo->prepare("UPDATE on_treinamento
        SET status = 1
        WHERE id = ?
    ");
        $sql->execute([$registro['id']]);
    }

    echo json_encode([
        "sucesso" => true
    ]);


} catch (PDOException $e) {

    echo json_encode([
        "sucesso" => false,
        "msg" => $e->getMessage()
    ]);
}

