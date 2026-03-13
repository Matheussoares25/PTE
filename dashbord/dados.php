<?php
header("Content-Type: application/json");
include "../control/conn.php";


try {

    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $cursos = $pdo->query("SELECT COUNT(*) FROM treinamentos")->fetchColumn();
    $alunos = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    $provas = $pdo->query("SELECT COUNT(*) FROM use_prova")->fetchColumn();
    $matriculas = $pdo->query("SELECT COUNT(*) FROM use_treinamentos where status_curso = 1")->fetchColumn();


    $qtd_porcento = $pdo->query("SELECT sum(porcentagem) FROM use_prova")->fetchColumn();
    $qtd_provas = $pdo->query("SELECT COUNT(*) FROM use_prova")->fetchColumn();

    $acertagem = $qtd_porcento / $qtd_provas;



    $dados = [
        "cursos" => $cursos,
        "alunos" => $alunos,
        "provas" => $provas,
        "matriculas" => $matriculas,
        "acertagem" => $acertagem

    ];

    echo json_encode($dados);

} catch (PDOException $e) {

    echo json_encode([
        "erro" => $e->getMessage()
    ]);
}
