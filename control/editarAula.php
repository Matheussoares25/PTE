<?php
header("Content-Type: application/json");
include "conn.php";
include "authADM.php";

try {
    $pdo = (new Conexao())->conn;

    $idAula = intval($_POST["idAula"] ?? 0);
    $idModulo = intval($_POST["idModulo"] ?? 0);
    $nomeAula = trim($_POST["nomeAula"] ?? '');
    $desc = $_POST['desc'] ?? '';
    $video = $_FILES['video'] ?? null;


    $sql = $pdo->prepare(" UPDATE aulas SET nome_aula = :nomeAula
        WHERE id = :idAula AND id_modulo = :idModulo
    ");
    $sql->execute([
        ":nomeAula" => $nomeAula,
        ":idAula" => $idAula,
        ":idModulo" => $idModulo
    ]);

    $sqlSelect = $pdo->prepare("SELECT id FROM midias WHERE id_aula = :idAula LIMIT 1
    ");
    $sqlSelect->bindValue(":idAula", $idAula);
    $sqlSelect->execute();
    $midiaExiste = $sqlSelect->fetch(PDO::FETCH_ASSOC);


    if ($midiaExiste) {
        if (!$desc == "") {
            $sqlDesc = $pdo->prepare("UPDATE midias SET desc_midia = :descM WHERE id_aula = :idAula
        ");
            $sqlDesc->execute([
                ":descM" => $desc,
                ":idAula" => $idAula

            ]);
        } else {
        }
        ;
    } else {
        $sqlDesc = $pdo->prepare("INSERT INTO midias (id_aula, desc_midia) 
            VALUES (:idAula, :descM)
        ");
        $sqlDesc->execute([
            ":idAula" => $idAula,
            ":descM" => $desc
        ]);
    }


    if (!empty($video["tmp_name"])) {

        $ext = pathinfo($video["name"], PATHINFO_EXTENSION);
        $nomeArquivo = "aula_" . $idAula . "." . $ext;

        $diretorio = __DIR__ . "/../uploads/videos/";
        $caminhoFinal = $diretorio . $nomeArquivo;
        $caminhoBanco = "/uploads/videos/" . $nomeArquivo;

        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0755, true);
        }

        if (!move_uploaded_file($video["tmp_name"], $caminhoFinal)) {
            throw new Exception("Erro ao salvar o vídeo no servidor");
        }

        if ($midiaExiste) {
            $sql = $pdo->prepare("
            UPDATE midias 
            SET caminho_video = :caminho
            WHERE id_aula = :idAula
        ");
        } else {
            $sql = $pdo->prepare("
            INSERT INTO midias (id_aula, caminho_video)
            VALUES (:idAula, :caminho)
        ");
        }

        $sql->execute([
            ":idAula" => $idAula,
            ":caminho" => $caminhoBanco
        ]);
    }

    echo json_encode(["sucesso" => true]);

} catch (Exception $e) {
    echo json_encode([
        "sucesso" => false,
        "erro" => $e->getMessage()
    ]);
}
