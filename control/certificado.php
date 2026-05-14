<?php

require_once "../config/conn.php";
include("auth.php");


$conexao = new Conexao();
$pdo = $conexao->conn;

$idUsuario = $_GET["idUser"] ?? null;
$idCurso = $_GET["nomeCurso"] ?? null;
$idCertificado = $_GET["idCertificado"] ?? null;






$sql = $pdo->prepare("
    SELECT a.token,a.Curso, a.emitido, a.id_user, b.nome AS nome_usuario, c.nome AS nome_curso
FROM certificado AS a
LEFT JOIN usuarios AS b  ON a.id_user = b.id
LEFT JOIN treinamentos AS c ON a.Curso = c.id
WHERE  a.id_user = :idUser  AND a.Curso = :curso or a.id = :idCertificado
");

$sql->bindParam(":idUser", $idUsuario, PDO::PARAM_INT);
$sql->bindParam(":curso", $idCurso, PDO::PARAM_INT);
$sql->bindParam(":idCertificado", $idCertificado, PDO::PARAM_INT);

$sql->execute();

$certificados = $sql->fetch(PDO::FETCH_ASSOC);

if (!$certificados) {
    die("Certificado não encontrado");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Certificado</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {

            width: 100%;
            height: 100vh;

            overflow: hidden;

            font-family: 'Poppins', sans-serif;

            background: linear-gradient(45deg, #2379f9, #cfd9ff);

            background-size: cover;
            background-position: center;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .certificado {

            width: min(95vw, 1600px);

            height: min(92vh, 900px);

            background: rgba(255, 255, 255, 0.97);

            border-radius: 25px;

            position: relative;

            overflow: hidden;

            border: 12px solid #0d6efd;

            box-shadow:
                0 0 30px rgba(0, 0, 0, 0.3),
                inset 0 0 20px rgba(13, 110, 253, 0.08);

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            text-align: center;

            padding: 4vw;
        }

        .certificado::before {

            content: "";

            position: absolute;

            inset: 15px;

            border: 3px solid rgba(13, 110, 253, 0.2);

            border-radius: 15px;
        }

        .logo-certificado {

            position: absolute;

            top: 2vw;
            left: 2vw;

            width: clamp(80px, 10vw, 160px);

            object-fit: contain;

            opacity: 0.3;

            filter: grayscale(10%);

            z-index: 2;
        }

        .topo {

            position: absolute;

            top: 3vh;

            width: 100%;
        }

        .titulo {

            font-size: clamp(38px, 5vw, 72px);

            font-weight: 700;

            color: #0d6efd;

            letter-spacing: 8px;

            text-shadow:
                0 3px 8px rgba(13, 110, 253, 0.25);
        }

        .subtitulo {

            margin-top: 10px;

            font-size: clamp(16px, 2vw, 24px);

            color: #666;

            font-weight: 300;

            letter-spacing: 4px;
        }

        .conteudo {

            z-index: 5;

            width: 100%;
        }

        .texto {

            font-size: clamp(18px, 2vw, 30px);

            color: #444;

            margin-top: 25px;
        }

        .aluno {

            margin-top: 30px;

            font-size: clamp(32px, 5vw, 70px);

            font-weight: 700;

            color: #111;

            text-transform: uppercase;

            text-shadow:
                0 3px 8px rgba(0, 0, 0, 0.10);
        }

        .descricao {

            margin-top: 30px;

            font-size: clamp(22px, 2vw, 22px);

            color: #444;
        }

        .curso {

            margin-top: 25px;

            font-size: clamp(24px, 3.5vw, 48px);

            color: #0d6efd;

            font-weight: 600;
        }

        .footer {

            position: absolute;

            bottom: 40px;

            width: 100%;

            display: flex;
            justify-content: space-around;
            align-items: center;

            padding: 0 50px;

            z-index: 5;
        }

        .assinatura-box {

            text-align: center;
        }

        .img-assinatura {

            width: 350px;

            height: auto;

            margin-bottom: -15px;

            z-index: 2;

            position: relative;
        }

        .linha {

            width: 320px;

            border-top: 2px solid #222;

            margin: 0 auto 10px auto;
        }

        .assinatura {

            font-size: clamp(14px, 1.5vw, 20px);

            color: #333;
        }

        .data {

            font-size: clamp(16px, 1.6vw, 22px);

            color: #444;
        }

        .selo {

            position: absolute;

            right: 4vw;
            bottom: 5vh;

            width: clamp(90px, 10vw, 140px);

            height: clamp(90px, 10vw, 140px);

            border-radius: 50%;

            border: 8px solid #0d6efd;

            display: flex;
            justify-content: center;
            align-items: center;

            color: #0d6efd;

            font-size: clamp(14px, 1.5vw, 22px);

            font-weight: bold;

            transform: rotate(-15deg);

            background: rgba(13, 110, 253, 0.08);

            box-shadow:
                0 0 20px rgba(13, 110, 253, 0.2);

            z-index: 5;
        }

        @media(max-width:900px) {

            body {
                padding: 10px;
            }

            .certificado {
                height: auto;
                min-height: 95vh;
                padding: 30px 20px;
            }

            .topo {
                position: relative;
                top: 0;
                margin-bottom: 40px;
            }

            .footer {

                position: relative;

                margin-top: 50px;

                bottom: 0;

                flex-direction: column;

                gap: 25px;
            }

            .selo {
                display: none;
            }

            .logo-certificado {
                opacity: 0.08;
            }
        }
    </style>
</head>

<body>

    <div class="certificado">

        <img src="../public/marca-1.png" class="logo-certificado">

        <div class="topo">

            <div class="titulo">
                CERTIFICADO
            </div>

            <div class="subtitulo">
                CERTIFICADO DE CONCLUSÃO PTE
            </div>

        </div>

        <div class="conteudo">

            <div class="texto">
                Certificamos que
            </div>

            <div class="aluno">
                <?= htmlspecialchars($certificados["nome_usuario"]) ?>
            </div>

            <div class="descricao">
                <i style="font-size=5px"> concluiu com excelência o treinamento atraves da plataforma de Treinamento e
                    Evolução (PTE);</i>
            </div>

            <div class="curso">
                <?= htmlspecialchars($certificados["nome_curso"]) ?>
            </div>

            <div class="texto">
                com participação e aproveitamento satisfatório.
            </div>

        </div>

        <div class="footer">

            <div class="assinatura-box">
                <img src="../public/MatheusPTE (1).png" class="img-assinatura">
                <div class="linha"></div>

                <div class="assinatura">

                    Assinatura Responsável
                </div>

            </div>

            <div class="data">

                Emitido em:
                <?= date("d/m/Y") ?>

            </div>



        </div>


        <div class="selo">
            APROVADO


        </div>

        <div class="codigo">

            Registro:
            <?= htmlspecialchars($certificados["token"]) ?>

        </div>





    </div>



</body>

</html>