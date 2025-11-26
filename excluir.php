<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('content-type: application/json; charset=utf-8');

include("conn.php");

$dados = json_decode(file_get_contents("php://input"), true);

if($_SERVER['REQUEST_METHOD']=== 'POST'){
    $dados =json_decode(file_get_contents("php://input"), true);
    $email = $dados['email'] ?? '';

    $sql = "UPDATE usuarios SET ativos = 0 Where email = '$email'";
    


    $res =mysqli_query($conexao,$sql);

    if($res){
        echo json_encode(["sucesso" => true]);
    }else{
        echo json_encode(['sucesso' => false]);
    }
}

?>