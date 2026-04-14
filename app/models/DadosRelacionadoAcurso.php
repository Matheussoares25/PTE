<?php
require_once __DIR__ . "/../../config/conn.php";

class DadosDoCurso
{

    private $pdo;

    public function __construct()
    {
        $conexao = new conexao();
        $this->pdo = $conexao->conn;
    }

    public function buscarQuestoes($idProva)
    {

        $sql = $this->pdo->prepare("SELECT 
    q.id AS id_questao,
    q.pergunta,
    a.id AS id_alternativa,
    a.correta as correta,
    a.texto
    FROM questoes q
    LEFT JOIN alternativas a 
    ON a.id_questao = q.id
    WHERE q.id_prova = :idProva
    ORDER BY q.id, a.id");

        $sql->bindValue(":idProva", $idProva);
        $sql->execute();

        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

        $questoes = [];

        foreach ($resultado as $linha) {

            $id = $linha["id_questao"];

            if (!isset($questoes[$id])) {
                $questoes[$id] = [
                    "id" => $id,
                    "pergunta" => $linha["pergunta"],
                    "alternativas" => []
                ];
            }

            if (!empty($linha["id_alternativa"])) {
                $questoes[$id]["alternativas"][] = [
                    "id_alternativa" => $linha["id_alternativa"],
                    "texto" => $linha["texto"],
                    "correta" => $linha["correta"]
                ];
            }
        }

        $questoes = array_values($questoes);

        return ([
            "sucesso" => true,
            "Questoes" => $questoes,
            "idProva" => $idProva
        ]);

    }
    public function buscarQuestoesComum($idProva){
         $sql = $this->pdo->prepare("SELECT 
    q.id AS id_questao,
    q.pergunta,
    a.id AS id_alternativa,
    a.texto
    FROM questoes q
    LEFT JOIN alternativas a 
    ON a.id_questao = q.id
    WHERE q.id_prova = :idProva
    ORDER BY q.id, a.id");

        $sql->bindValue(":idProva", $idProva);
        $sql->execute();

        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

        $questoes = [];

        foreach ($resultado as $linha) {

            $id = $linha["id_questao"];

            if (!isset($questoes[$id])) {
                $questoes[$id] = [
                    "id" => $id,
                    "pergunta" => $linha["pergunta"],
                    "alternativas" => []
                ];
            }

            if (!empty($linha["id_alternativa"])) {
                $questoes[$id]["alternativas"][] = [
                    "id_alternativa" => $linha["id_alternativa"],
                    "texto" => $linha["texto"],
              
                ];
            }
        }

        $questoes = array_values($questoes);

        return ([
            "sucesso" => true,
            "Questoes" => $questoes,
            "idProva" => $idProva
        ]);

    }
}

?>