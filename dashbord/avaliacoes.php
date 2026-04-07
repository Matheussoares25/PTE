<?php
header("Content-Type: application/json");
include "../control/conn.php";
include "../control/authADM.php";

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;


    if (isset($_GET["action"]) && $_GET['action'] === "avaliar") {
        $idProva = $_POST["idDohistoricoProva"];
        $nota = $_POST["nota"];
        $idUser = $_POST["id_user"];
        $idProvaBanco = $_POST["idDaProvanoBanco"];

        $Verifica = $pdo->prepare("SELECT * FROM notas WHERE id_prova = :idProva AND id_aluno = :idAluno");
        $Verifica->bindParam(":idProva", $idProvaBanco);
        $Verifica->bindParam(":idAluno", $idUser);
        $Verifica->execute();

        if ($Verifica->rowCount() > 0) {


            $sql = $pdo->prepare("UPDATE use_prova SET nota = :nota WHERE id = :idProva ");
            $sql->bindParam(":nota", $nota);
            $sql->bindParam("idProva", $idProva);
            $sql->execute();


            $sqlUpdate = $pdo->prepare("UPDATE notas SET nota = :nota WHERE id_prova = :idProva");
            $sqlUpdate->bindParam(":nota", $nota);
            $sqlUpdate->bindParam(":idProva", $idProva);
            $sqlUpdate->execute();

            echo json_encode(["update" => true]);
            return;

        } else {
            $sqlInsert = $pdo->prepare("INSERT INTO notas (id_prova, id_aluno, nota) VALUES (:idProva, :idAluno, :nota)");
            $sqlInsert->bindParam(":idProva", $idProvaBanco);
            $sqlInsert->bindParam(":idAluno", $idUser);
            $sqlInsert->bindParam(":nota", $nota);
            $sqlInsert->execute();



            $sql = $pdo->prepare("UPDATE use_prova SET nota = :nota WHERE id = :idProva ");
            $sql->bindParam(":nota", $nota);
            $sql->bindParam("idProva", $idProva);
            $sql->execute();

            echo json_encode(["sucesso" => true]);
            return;

        }

    }

    if (isset($_GET["action"]) && $_GET['action'] === "excluir") {
        $idProva = $_POST["idDohistoricoProva"];
        $idUser = $_POST["id_user"];
        $idProvaBanco = $_POST["idDaProvanoBanco"];

        $sql = $pdo->prepare("DELETE FROM use_prova WHERE id = :idProva AND id_user = :idAluno");
        $sql->bindParam(":idProva", $idProva);
        $sql->bindParam(":idAluno", $idUser);
        $sql->execute();

        echo json_encode(["excluido" => true]);
        return;
    }

    $sql = $pdo->query("SELECT 
    a.id,
    a.nota,
    a.id_user,
    a.id_prova,
    a.acertos,
    a.data_inicio,
    a.aprovado,
    a.porcentagem,
    a.qtd_questoes,
    b.nome_aula,
    c.nome,
    t.id AS id_curso,

    (
        SELECT COUNT(a2.id)
        FROM modulos m2 
        INNER JOIN aulas a2 ON a2.id_modulo = m2.id 
        WHERE m2.id_curso = t.id
          AND a2.tipo = 2
    ) AS total_provas,

    ROW_NUMBER() OVER (
        PARTITION BY t.id   
        ORDER BY b.id
    ) AS ordem_prova

    FROM use_prova a
    LEFT JOIN aulas b ON b.id = a.id_prova
    LEFT JOIN usuarios c ON c.id = a.id_user
    LEFT JOIN modulos m ON m.id = b.id_modulo
    LEFT JOIN treinamentos t ON t.id = m.id_curso;
    ");
    $avaliacoes = $sql->fetchAll(PDO::FETCH_ASSOC);

    

   

    $total = count($avaliacoes);

    $dados = [
        "success" => true,
        "avaliacoes" => $avaliacoes,
        "tAvaliacoes" => $total,
  

    ];

    echo json_encode($dados);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
?>