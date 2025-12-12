<?php
header("Content-Type: application/json");
include "conn.php";
include "authADM.php";

try {
    $pdo = (new Conexao())->conn;

    $idAula   = intval($_POST["idAula"] ?? 0);
    $idModulo = intval($_POST["idModulo"] ?? 0);
    $nomeAula = trim($_POST["nomeAula"] ?? '');


    $sql = $pdo->prepare("UPDATE Aulas SET nome_aula = :nomeAula WHERE id = :idAula AND id_modulo = :idModulo
    ");
    $sql->execute([
        ":nomeAula" => $nomeAula,
        ":idAula"   => $idAula,
        ":idModulo" => $idModulo
    ]);


    if (isset($_FILES["video"]) && $_FILES["video"]["error"] === UPLOAD_ERR_OK && $_FILES["video"]["size"] > 0) {

        $tmp = $_FILES["video"]["tmp_name"];


        $stream = fopen($tmp, 'rb');

        $ins = $pdo->prepare("INSERT INTO Midias (id_aula, conteudo) VALUES (:idAula, :conteudo)
        ");

        $ins->bindParam(":idAula", $idAula, PDO::PARAM_INT);
        $ins->bindParam(":conteudo", $stream, PDO::PARAM_LOB);
        $ins->execute();

        if (is_resource($stream)) {
            fclose($stream);
        }
    }

    echo json_encode(["sucesso" => true]);

} catch (Exception $e) {
    echo json_encode([
        "sucesso" => false,
        "erro" => $e->getMessage()
    ]);
}
