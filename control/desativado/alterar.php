<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("conn.php");
// $dados = json_decode(file_get_contents("php://input"), true);


header(header: 'Content-Type: application/json; charset=utf-8');


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailAntigo = $_POST['emailAntigo'] ?? '';
    $novoEmail = $_POST['novoEmail'] ?? '';
    $novaSenha = $_POST['novaSenha'] ??'';
    $senhaAntiga = $_POST['senhaAntiga'] ??'';
    $foto = $_FILES['foto'] ?? null;







//verifica se ja existe um usuario com esse mail
$Verifica = "SELECT * FROM usuarios WHERE email = '$novoEmail' AND email != '$emailAntigo'";
    $resultado = mysqli_query($conexao, $Verifica);

    if (mysqli_num_rows($resultado) > 0) {
        echo json_encode(["sucesso" => false]);
        exit;

    }


//verifico no banco de dados atraves do email, se a senha esta certa;
$sqlSenha = "SELECT Senha FROM usuarios WHERE Email = '$emailAntigo'";
$resSenha = mysqli_query($conexao, $sqlSenha);
$banco= mysqli_fetch_assoc($resSenha);


if ($banco["Senha"] !== md5($senhaAntiga)) {
    echo json_encode(["sucesso" => false, "sErrada" => true]);
    exit;
}


$senhaHash = MD5($novaSenha);

if ($foto && $foto['error'] === 0) {
    $conteudo = file_get_contents($foto['tmp_name']);
    $conteudo = mysqli_real_escape_string($conexao, $conteudo);

    mysqli_query($conexao, "
        UPDATE usuarios 
        SET Email='$novoEmail', Senha='$senhaHash', Foto='$conteudo'
        WHERE Email='$emailAntigo'
    ");
} else {
    mysqli_query($conexao, "
        UPDATE usuarios 
        SET Email='$novoEmail', Senha='$senhaHash'
        WHERE Email='$emailAntigo'
    ");
}
echo json_encode(["sucesso" => true]);




}
?>