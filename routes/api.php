<?php

require_once __DIR__ . "/../app/controllers/UsuarioController.php";
require_once __DIR__ . "/../app/controllers/NoticiaController.php";
require_once __DIR__ . "/../app/controllers/VagaController.php";
require_once __DIR__ . "/../app/controllers/CursoController.php";


$acao = $_GET['acao'] ?? '';

if ($acao == "cadastrarUsuario") {
    $controller = new UsuarioController();
    $controller->cadastrar();
}

if ($acao == "login") {
    $controller = new UsuarioController();
    $controller->login();
}

if ($acao == "salvarNoticia") {
    $controller = new NoticiaController();
    $controller->salvar();
}
if ($acao == "buscarNoticias") {
    $controller = new NoticiaController();
    $controller->buscar();
}
if ($acao == "excluirNoticia") {
    $controller = new NoticiaController();
    $controller->delete();
}
if ($acao == "buscarVagas") {
    $controller = new VagaController();
    $controller->buscar();
}
if ($acao == "candidatar") {
    $controller = new VagaController();
    $controller->candidatura();
}
if ($acao == "buscarCursosDoALuno") {
    $controller = new CursoController();
    $controller->buscarCursosDoAluno();
}
if ($acao == "buscarCursosConcluidosDoALuno") {
    $controller = new CursoController();
    $controller->buscarCursosConcluidosDoAluno();
}
if ($acao == "buscarCursosADM") {
    $controller = new CursoController();
    $controller->buscarCursosGeralADM();

}
if ($acao == "buscarPorIdCurso") {
    $controller = new CursoController();
    $controller->buscaPorId();
}
if ($acao == "cadAoCurso") {
    $controller = new CursoController();
    $controller->cadastrarAoCurso();
}
if ($acao == "deletarMatricula") {
    $controller = new CursoController();
    $controller->deletarMatircula();
}
if ($acao == "listarCursosAdm"){
    $controller = new CursoController();
    $controller->listarCursosComEstrutura();
}

if ($acao == "criaModulo") {
    $controller = new CursoController();
    $controller->inserirModulo();

}

?>