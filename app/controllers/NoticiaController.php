<?php
require_once __DIR__ . "/../models/Noticia.php";
require_once __DIR__ . "/../models/Vaga.php";


class NoticiaController
{

    private $noticia;
    private $vaga;

    public function __construct()
    {
        $this->noticia = new Noticia();
        $this->vaga = new Vaga();
    }

    public function salvar()
    {
        require_once __DIR__ . "/../../control/authADM.php";

        header('Content-Type: application/json; charset=utf-8');

        date_default_timezone_set('America/Sao_Paulo');

        $titulo = $_POST['titulo'] ?? '';
        $conteudo = $_POST['conteudo'] ?? '';
        $vaga = $_POST['vaga'] ?? 0;

        if ($titulo == "" || $conteudo == "") {
            echo json_encode(["vazio" => true]);
            return;
        }

        try {


            if ($vaga == 1) {
                $this->vaga->inserir($titulo, $conteudo);
            }

            $data = date('Y-m-d H:i:s');

            $ok = $this->noticia->inserir($titulo, $conteudo, $data, $vaga);

            if ($ok) {
                echo json_encode(["sucesso" => true]);
            }

        } catch (PDOException $e) {
            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }

    public function buscar()
    {
        require_once __DIR__ . "/../../control/auth.php";

        header('Content-Type: application/json; charset=utf-8');

        try {
            $res = $this->noticia->buscarNoticias();
            echo json_encode($res);
        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "erro" => $e->getMessage()
            ]);
        }
    }

    public function delete()
    {
        require_once __DIR__ . "/../../control/authADM.php";

        header('Content-Type: application/json; charset=utf-8');

        $id = $_POST['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            echo json_encode([
                "success" => false,
                "erro" => "ID inválido"
            ]);
            return;
        }

        try {
            $res = $this->noticia->deleteNoticias($id);

            if ($res) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false]);
            }

        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "erro" => $e->getMessage()
            ]);
        }
    }
}
?>