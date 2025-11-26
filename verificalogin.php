
<?php





ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



include("conn.php");

header('Content-Type: application/json; charset=utf-8');



$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';




    $sql = "SELECT senha FROM usuarios WHERE Email = '".$email."' AND senha = MD5('".$senha."')";


    $bd = mysqli_query($conexao, $sql);

    if ($bd && mysqli_num_rows($bd) > 0) {
        $response = ['success' => true];
    } else {
        $response = ['success' => false];
    }




    mysqli_close($conexao);
}

echo json_encode($response);
?>
