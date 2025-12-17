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
            m.nome_modolu AS nome_modulo,

            a.id AS id_aula,
            a.nome_aula AS nome_aula

        FROM treinamentos t
        LEFT JOIN modulos m 
            ON m.id_curso = t.id

        LEFT JOIN aulas a 
            ON a.id_modulo = m.id
           AND a.excluido = 0

        ORDER BY t.id DESC, m.id ASC, a.id ASC
    ");

    $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $resultado = [];

    foreach ($linhas as $row) {

        $tid = $row['id_treinamento'];
        $mid = $row['id_modulo'];

        // Curso
        if (!isset($resultado[$tid])) {
            $resultado[$tid] = [
                'id' => $tid,
                'nome' => $row['nome_treinamento'],
                'modulos' => []
            ];
        }

        // Módulo (MESMO SEM AULA)
        if ($mid && !isset($resultado[$tid]['modulos'][$mid])) {
            $resultado[$tid]['modulos'][$mid] = [
                'id_modulo' => $mid,
                'nome_modulo' => $row['nome_modulo'],
                'aulas' => []
            ];
        }

        // Aula (só se existir)
        if (!empty($row['id_aula'])) {
            $resultado[$tid]['modulos'][$mid]['aulas'][] = [
                'id_aula' => $row['id_aula'],
                'nome_aula' => $row['nome_aula']
            ];
        }
    }

    // Reindexa arrays
    foreach ($resultado as &$curso) {
        $curso['modulos'] = array_values($curso['modulos']);
    }

    echo json_encode([
        'sucesso' => true,
        'cursos' => array_values($resultado)
    ]);

} catch (Exception $e) {
    echo json_encode([
        'sucesso' => false,
        'erro' => $e->getMessage()
    ]);
}
