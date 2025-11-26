<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include("conn.php");

header(header: 'Content-Type: application/json; charset=utf-8');



$response = ['success' => false];



if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $foto = $_FILES['foto'] ?? null;


    if (!isset($_FILES['foto'])) {
        echo json_encode(["sucesso" => false, "mensagem" => true]);
        exit;
        
    }

    $conteudo = file_get_contents($_FILES['foto']['tmp_name']);
    $conteudo = mysqli_real_escape_string($conexao, $conteudo);


    //essa query verifica se ja existe alguem com esse email
    $verificar = "SELECT * FROM usuarios WHERE email = '$email'";
    $res = mysqli_query($conexao, $verificar);

    if (mysqli_num_rows($res) > 0) {
        echo json_encode(["success" => false, 'Existe' => true]);
        exit;
    }


    $senhaHash = MD5($senha);






    //essa query Insere um novo cadastro no banco
    $sql = "INSERT INTO usuarios (Email,senha,ativos,Foto) VALUES ('$email','$senhaHash',1,'$conteudo')";

    $bd = mysqli_query($conexao, $sql);





    if ($bd) {
        $response = ['success' => true];
    }


}

echo json_encode($response);



?>