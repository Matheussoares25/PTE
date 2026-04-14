<?php
require_once __DIR__ . "/../../config/conn.php";

class ranking {

    private $pdo;

    public function __construct(){
        $conexao = new Conexao();
        $this->pdo = $conexao->conn;
    }

    public function ranking(){

        $sql = $this->pdo->prepare("
            SELECT 
                u.nome,
                u.Foto,
                SUM(up.nota) AS total_notas
            FROM use_prova up
            INNER JOIN usuarios u ON u.id = up.id_user
            GROUP BY u.id, u.nome, u.Foto
            ORDER BY total_notas DESC
        ");

        $sql->execute();

        $result = $sql->fetchAll(PDO::FETCH_ASSOC);


        foreach($result as &$u){
            if(!empty($u['Foto'])){
                $u['Foto'] = "data:image/jpeg;base64," . base64_encode($u['Foto']);
            }
        }

        return [
            "ranking" => $result
        ];
    }
}