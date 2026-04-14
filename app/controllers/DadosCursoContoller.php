<?php

require_once __DIR__ . "/../models/DadosRelacionadoAcurso.php";

class DadosCursoContoller
{
    private $cursoContoller;
    public function __construct()
    {
        $this->cursoContoller = new DadosDoCurso();
    }

    public function buscadorQuestoes()
    {

        session_start();

        $tipoUser = $_SESSION["tipo"];
        if ($tipoUser == 2) {
            $idProva = $_POST['idProva'] ?? "";

            $curso = $this->cursoContoller->buscarQuestoes($idProva);

            echo json_encode($curso);
        }
        if ($tipoUser == 1) {
            $idProva = $_POST["idProva"] ??"";
            
            $curso = $this->cursoContoller->buscarQuestoesComum($idProva);

            echo json_encode($curso);
        }


    }
}

?>