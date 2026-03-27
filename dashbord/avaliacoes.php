<?php
header("Content-Type: application/json");
include "../control/conn.php";
include "../control/authADM.php";

try {
    $conexao = new Conexao();
    $pdo = $conexao->conn;

    $sql = $pdo->query("SELECT a.id,a.nota,a.id_user,a.id_prova,a.acertos,a.data_inicio,a.aprovado,a.porcentagem,a.qtd_questoes,b.nome_aula,c.nome 
    FROM use_prova a 
    LEFT JOIN aulas b ON b.id = a.id_prova
    LEFT JOIN usuarios c ON c.id = a.id_user;
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
