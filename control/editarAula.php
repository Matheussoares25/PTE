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

    // Atualiza nome da aula
    $sql = $pdo->prepare("
        UPDATE Aulas SET nome_aula = :nomeAula
        WHERE id = :idAula AND id_modulo = :idModulo
    ");
    $sql->execute([
        ":nomeAula" => $nomeAula,
        ":idAula" => $idAula,
        ":idModulo" => $idModulo
    ]);

    // Verifica se já existe Midia para aula
    $sqlSelect = $pdo->prepare("
        SELECT id FROM Midias WHERE id_aula = :idAula LIMIT 1
    ");
    $sqlSelect->bindValue(":idAula", $idAula);
    $sqlSelect->execute();
    $midiaExiste = $sqlSelect->fetch(PDO::FETCH_ASSOC);

    // Se existe, atualiza descrição
    if ($midiaExiste) {
        $sqlDesc = $pdo->prepare("
            UPDATE Midias SET desc_midia = :descM WHERE id_aula = :idAula
        ");
        $sqlDesc->execute([
            ":descM" => $desc,
            ":idAula" => $idAula
        ]);
    } 
    // Se não existe, cria novo registro
    else {
        $sqlDesc = $pdo->prepare("
            INSERT INTO Midias (id_aula, desc_midia) 
            VALUES (:idAula, :descM)
        ");
        $sqlDesc->execute([
            ":idAula" => $idAula,
            ":descM" => $desc
        ]);
    }

    // Se houver vídeo enviado, atualiza/insere vídeo
    if (!empty($video["tmp_name"])) {

        $stream = fopen($video["tmp_name"], "rb");

        if ($midiaExiste) {
            // Atualiza conteudo da midia existente
            $sql = $pdo->prepare("
                UPDATE Midias SET conteudo = :conteudo
                WHERE id_aula = :idAula
            ");
        } else {
            // Cria nova midia com conteúdo
            $sql = $pdo->prepare("
                INSERT INTO Midias (id_aula, conteudo)
                VALUES (:idAula, :conteudo)
            ");
        }

        $sql->bindParam(":idAula", $idAula, PDO::PARAM_INT);
        $sql->bindParam(":conteudo", $stream, PDO::PARAM_LOB);
        $sql->execute();

        fclose($stream);
    }

    echo json_encode(["sucesso" => true]);

} catch (Exception $e) {
    echo json_encode([
        "sucesso" => false,
        "erro" => $e->getMessage()
    ]);
}
