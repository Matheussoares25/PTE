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

   
    $sql = $pdo->prepare("UPDATE aulas SET nome_aula = :nomeAula  WHERE id = :idAula AND id_modulo = :idModulo");
    $sql->execute([
        ":nomeAula" => $nomeAula,
        ":idAula" => $idAula,
        ":idModulo" => $idModulo
    ]);


    $sqlSelect = $pdo->prepare("SELECT id FROM midias WHERE id_aula = :idAula LIMIT 1");
    $sqlSelect->execute([":idAula" => $idAula]);
    $midiaExiste = $sqlSelect->fetch(PDO::FETCH_ASSOC);


    $diretorio = __DIR__ . "/../uploads/videos/";
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0755, true);
    }


    $caminhoBanco = null;
    if (!empty($video["tmp_name"])) {
        $ext = pathinfo($video["name"], PATHINFO_EXTENSION);
        $nomeArquivo = "aula_" . $idAula . "." . $ext;
        $caminhoFinal = $diretorio . $nomeArquivo;
        $caminhoBanco = "/PTE/uploads/videos/" . $nomeArquivo;

        

        if (!move_uploaded_file($video["tmp_name"], $caminhoFinal)) {
            throw new Exception("Erro ao salvar o vídeo no servidor");
        }
    }

  
    if (!$midiaExiste) {
        $sqlInsert = $pdo->prepare("
            INSERT INTO midias (id_aula, desc_midia, caminho_video)
            VALUES (:idAula, '', '')
        ");
        $sqlInsert->execute([":idAula" => $idAula]);
    }


    $sqlUpdate = $pdo->prepare("
        UPDATE midias 
        SET desc_midia = :descM,
            caminho_video = COALESCE(:caminho, caminho_video)
        WHERE id_aula = :idAula
    ");
    $sqlUpdate->execute([
        ":descM" => $desc,
        ":caminho" => $caminhoBanco, 
        ":idAula" => $idAula
    ]);

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "erro" => $e->getMessage()
    ]);
}