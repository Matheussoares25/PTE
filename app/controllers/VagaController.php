<?php
require_once __DIR__ . "/../../config/conn.php";

class VagaController
{
    private $vaga;
    public function __construct()
    {
        $this->vaga = new Vaga();
    }

    public function buscar()
    {
        require_once __DIR__ . "/../../control/auth.php";
        header('Content-Type: application/json; charset=utf-8');
        try {
            $res = $this->vaga->buscar();
            echo json_encode($res);
        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "erro" => $e->getMessage()
            ]);
        }
    }
    public function buscarCandidaturaPorVaga(){

     require_once __DIR__ . "/../../control/auth.php";

     header('Content-Type: application/json; charset=utf-8');

     $idVaga = $_POST['id'] ?? null;

     

     $res = $this->vaga->buscarCandidaturas($idVaga);

     echo json_encode($res);

    }

    public function candidatura()
    {
        require_once __DIR__ . "/../../control/auth.php";

        header('Content-Type: application/json; charset=utf-8');

        $idUser = $_POST['iduser'] ?? "";
        $idVaga = $_POST['idvaga'] ?? null;



        try {
            $res = $this->vaga->buscarCandidaturas($idUser, $idVaga);
            if (count($res) > 0) {
                echo json_encode(["Existe" => true]);
                return;
            }   

            $insert = $this->vaga->inserirCandidatura($idUser, $idVaga);
            if ($insert) {
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

    public function deletaVaga(){
        require_once __DIR__ . "/../../control/authADM.php";

        header("Content-Type: application/json; charset=utf-8");

        $idVaga = $_POST["idVaga"] ??"";

 
        $res = $this->vaga->deletarVaga($idVaga);
        
        if($res){
            echo json_encode(["success"=> true]);
        }else{
            echo json_encode(["success"=> false]);
        }
    }
}