<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header('content-type: application/json; charset=utf-8');

include("conn.php");

$sql = "SELECT * FROM usuarios where ativos = 1";
// $sql = "SELECT * FROM usuarios ORDER BY Email";

$res = $conexao->query($sql);

$cadastrados=array();

if($res -> num_rows > 0){
    while($row = $res->fetch_assoc()){

            if (!empty($row['Foto'])) {
            $row['Foto'] = 'data:image/jpeg;base64,' . base64_encode($row['Foto']);
        } else {
            
            $row['Foto'] = 'img/padrao.png';
        }

        $cadastrados[] = $row;
    }
}
echo json_encode($cadastrados, JSON_UNESCAPED_UNICODE);


?>