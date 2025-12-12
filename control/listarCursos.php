<?php
header("Content-Type: application/json");
include("conn.php");
include("authADM.php");

try {
    $pdo = (new Conexao())->conn;

   
    $stmt = $pdo->query("
        SELECT 
            t.id AS id_treinamento,
            t.nome AS nome_treinamento,
            m.id AS id_modulo,
            m.nome_modolu AS nome_modolu,
            a.id AS id_aula,
            a.nome_aula AS nome_aula,
            a.id_modulo
        FROM treinamentos t
        LEFT JOIN Modulos m 
               ON m.id_curso = t.id
        LEFT JOIN Aulas a 
               ON a.id_modulo = m.id
        ORDER BY t.id DESC, m.id ASC, a.id ASC
    ");

    $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $resultado = [];

    foreach ($linhas as $row) {

        $tid = $row['id_treinamento'];
        $mid = $row['id_modulo'];

        if (!isset($resultado[$tid])) {
            $resultado[$tid] = [
                'id' => $tid,
                'nome' => $row['nome_treinamento'],
                'modulos' => []
            ];
        }


        if ($mid && !isset($resultado[$tid]['modulos'][$mid])) {
            $resultado[$tid]['modulos'][$mid] = [
                'id_modulo' => $mid,
                'nome_modulo' => $row['nome_modolu'],
                'aulas' => []
            ];
        }


        if ($row['id_aula']) {
            $resultado[$tid]['modulos'][$mid]['aulas'][] = [
                'id_aula' => $row['id_aula'],
                'nome_aula' => $row['nome_aula']
            ];
        }
    }


    foreach ($resultado as &$curso) {
        $curso['modulos'] = array_values($curso['modulos']);
    }
    $resultado = array_values($resultado);


    echo json_encode([
        'sucesso' => true,
        'cursos' => $resultado
    ]);

} catch (Exception $e) {
    echo json_encode([
        'sucesso' => false,
        'erro' => $e->getMessage()
    ]);
}
