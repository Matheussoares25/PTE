<?php
header("Content-Type: application/json");
include "../control/conn.php";
include "../control/authADM.php";

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;

 
    if (isset($_GET['action']) && $_GET['action'] === 'exclusao') {

        $matricula = $_POST['matricula'] ?? null;

        if (!$matricula) {
            echo json_encode(["erro" => "Matrícula não enviada"]);
            exit;
        }

        $sql = $pdo->prepare("DELETE FROM use_treinamentos WHERE matricula = :matricula");
        $sql->bindParam(":matricula", $matricula, PDO::PARAM_STR);
        $sql->execute();

        echo json_encode(["status" => "sucesso"]);
        exit;
    }

  
    //  DASHBOARD (padrão)
    

    $cursos = $pdo->query("SELECT COUNT(*) FROM treinamentos")->fetchColumn();
    $tCursos = $pdo->query("SELECT * FROM treinamentos")->fetchAll();
    $alunos = $pdo->query("SELECT COUNT(DISTINCT id_usuario) FROM use_treinamentos")->fetchColumn();
    $provas = $pdo->query("SELECT COUNT(*) FROM use_prova")->fetchColumn();
    $usuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();

    $sql = $pdo->prepare("
        SELECT a.matricula,a.id_usuario,c.nome AS nome_usuario,
               a.id_curso,t.nome AS nome_curso,
               a.status_curso,a.data_curso,a.data_fim,a.modulo
        FROM use_treinamentos a
        INNER JOIN usuarios c ON a.id_usuario = c.id
        INNER JOIN treinamentos t ON a.id_curso = t.id;
    ");
    $sql->execute();
    $tAlunos = $sql->fetchAll();

    $sql2 = $pdo->prepare("
        SELECT a.id,a.id_user,u.nome AS nome_usuario,
               b.nome_aula AS nome_prova,
               a.acertos,a.porcentagem,
               a.data_conclusao,a.data_inicio,a.qtd_questoes
        FROM use_prova a
        INNER JOIN aulas b ON a.id_prova = b.id
        INNER JOIN usuarios u ON a.id_user = u.id
        ORDER BY a.id DESC;
    ");
    $sql2->execute();
    $dadosProvas = $sql2->fetchAll();

    $qtd_porcento = $pdo->query("SELECT SUM(porcentagem) FROM use_prova")->fetchColumn();
    $qtd_provas = $pdo->query("SELECT COUNT(*) FROM use_prova")->fetchColumn();

    $acertagem = ($qtd_porcento && $qtd_provas) 
        ? $qtd_porcento / $qtd_provas 
        : 0;

    $dados = [
        "cursos" => $cursos,
        "alunos" => $alunos,
        "provas" => $provas,
        "usuarios" => $usuarios,
        "acertagem" => $acertagem,
        "tCursos" => $tCursos,
        "tAlunos" => $tAlunos,
        "dProvas" => $dadosProvas
    ];

    echo json_encode($dados);

} catch (PDOException $e) {
    echo json_encode([
        "erro" => $e->getMessage()
    ]);
}

?>