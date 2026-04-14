<?php

require_once __DIR__ . "/../app/controllers/UsuarioController.php";
require_once __DIR__ . "/../app/controllers/NoticiaController.php";
require_once __DIR__ . "/../app/controllers/VagaController.php";
require_once __DIR__ . "/../app/controllers/CursoController.php";
require_once __DIR__ . "/../app/controllers/AdmController.php";
require_once __DIR__ . "/../app/controllers/DashController.php";
require_once __DIR__ . "/../app/controllers/RankingController.php";
require_once __DIR__ . "/../app/controllers/DadosCursoContoller.php";

$acao = $_GET['acao'] ?? '';


if( $acao == "buscarQuestoes"){
    $controller = new DadosCursoContoller();
    $controller->buscadorQuestoes();
}

if( $acao == 'ControleLogin'){
    $controller = new UsuarioController();
    $controller->chekarSessao();
}


if ($acao == "cadastrarUsuario") {
    $controller = new UsuarioController();
    $controller->cadastrar();
}

if ($acao == "login") {
    $controller = new UsuarioController();
    $controller->login();
}
if ($acao == "aceitarTermos") {
    $controller = new UsuarioController();
    $controller->verificarAcesso();
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
if ($acao == "buscarCandidaturas") {
    $controller = new VagaController();
    $controller->buscarCandidaturaPorVaga();
}
if ($acao == "excluirVaga"){
    $controller = new VagaController();
    $controller->deletaVaga();
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
if($acao == "listarModulos"){
    $controller = new CursoController();
    $controller->listarModulos();
}

if ($acao == "criaModulo") {
    $controller = new CursoController();
    $controller->inserirModulo();
}

if( $acao == "listarAulas"){
    $controller = new CursoController();
    $controller->listarAulas();
}
if ($acao == "abrirAula"){
    $controller = new CursoController();
    $controller->dadosAulaAberta();
}

if($acao == "checkinAdm"){
    $controller = new AdmController();
    $controller->get_adm();
}

if( $acao == "dashDeleteMatricula"){
    $controller = new DashController();
    $controller->DELETARMATRICULADECURSO();
}
if( $acao == "deleteModulo"){
    $controller = new CursoController();
    $controller->deletarModuloADM();
}
if( $acao == "criaUmaAula"){
    $controller = new CursoController();
    $controller->criarUmaAula();
}
if( $acao == "buscarRanking"){
    $controller = new RankingController();
    $controller->buscar();
}


?>