<?php
header("Content-Type: application/json");
include "../control/conn.php";


try {

    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $cursos = $pdo->query("SELECT COUNT(*) FROM treinamentos")->fetchColumn();
    $tCursos = $pdo->query("SELECT * FROM treinamentos")->fetchAll();
    $alunos = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    $provas = $pdo->query("SELECT COUNT(*) FROM use_prova")->fetchColumn();
    $matriculas = $pdo->query("SELECT COUNT(*) FROM use_treinamentos where status_curso = 1")->fetchColumn();



    //Dados alunos em cursos-------------------------------------------------------------------------
    $sql = $pdo->prepare("SELECT a.matricula,a.id_usuario,c.nome AS nome_usuario,a.id_curso,t.nome AS nome_curso,a.status_curso,a.data_curso,a.data_fim,
    a.modulo
    FROM use_treinamentos a
    INNER JOIN usuarios c ON a.id_usuario = c.id
    INNER JOIN treinamentos t ON a.id_curso = t.id;");
    $sql-> execute();

    $tAlunos = $sql->fetchAll();
    //-------------------------------------------------------------------------------------------
    //-------------------------------------------------------------------------------------------


    $qtd_porcento = $pdo->query("SELECT sum(porcentagem) FROM use_prova")->fetchColumn();
    $qtd_provas = $pdo->query("SELECT COUNT(*) FROM use_prova")->fetchColumn();

    if($qtd_porcento == null || $qtd_provas == null){
        $acertagem = 0;
    }else{
        $acertagem = $qtd_porcento / $qtd_provas;
    }

    


    if($matriculas == null){
        $matriculas = 0;
    }


    $dados = [
        "cursos" => $cursos,
        "alunos" => $alunos,
        "provas" => $provas,
        "matriculas" => $matriculas,
        "acertagem" => $acertagem,
        "tCursos" => $tCursos,
        "tAlunos" => $tAlunos

    ];

    echo json_encode($dados);

} catch (PDOException $e) {

    echo json_encode([
        "erro" => $e->getMessage()
    ]);
}
