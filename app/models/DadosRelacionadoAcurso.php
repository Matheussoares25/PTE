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
    public function buscarQuestoesComum($idProva)
    {
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

    public function verificarSeCursoExiste($nomeCurso)
    {

        $sql = $this->pdo->prepare("SELECT * FROM treinamentos WHERE nome = :nome");
        $sql->bindParam("nome", $nomeCurso);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;
    }

    public function cadastrarCurso($nomeCurso)
    {
        $sql = $this->pdo->prepare("INSERT INTO treinamentos (nome,status) VALUES (:nome,1)");
        $sql->bindParam("nome", $nomeCurso);
        return $sql->execute();
    }

    public function cadastarAula($idAula, $idModulo, $nomeAula, $desc, $video)
    {
        $sql = $this->pdo->prepare("UPDATE aulas SET nome_aula = :nomeAula WHERE id = :idAula AND id_modulo = :idModulo");
        $sql->execute([
            ":nomeAula" => $nomeAula,
            ":idAula" => $idAula,
            ":idModulo" => $idModulo
        ]);

        $sqlSelect = $this->pdo->prepare("SELECT id FROM midias WHERE id_aula = :idAula LIMIT 1");
        $sqlSelect->execute([":idAula" => $idAula]);
        $midiaExiste = $sqlSelect->fetch(PDO::FETCH_ASSOC);

       $diretorio = __DIR__ . "/../../uploads/videos/";
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0755, true);
        }

        $caminhoBanco = null;

        if (!empty($video["tmp_name"])) {
            $ext = pathinfo($video["name"], PATHINFO_EXTENSION);
            $nomeArquivo = "aula_" . $idAula . "." . $ext;
            $caminhoFinal = $diretorio . $nomeArquivo;
            $caminhoBanco = "/PTE/uploads/videos/" . $nomeArquivo;

            if (!move_uploaded_file($video["tmp_name"], $caminhoFinal)) {
                throw new Exception("Erro ao salvar o vídeo");
            }
        }

        if (!$midiaExiste) {
            $sqlInsert = $this->pdo->prepare("INSERT INTO midias (id_aula, desc_midia, caminho_video)
        VALUES (:idAula, '', '')");
            $sqlInsert->execute([":idAula" => $idAula]);
        }

        $sqlUpdate = $this->pdo->prepare("UPDATE midias 
        SET desc_midia = :descM,
            caminho_video = COALESCE(:caminho, caminho_video)
        WHERE id_aula = :idAula");

        $sqlUpdate->execute([
            ":descM" => $desc,
            ":caminho" => $caminhoBanco,
            ":idAula" => $idAula
        ]);

    
        return [
            "success" => true,
            "idAula" => $idAula,
            "caminhoVideo" => $caminhoBanco
        ];
    }
}

?>