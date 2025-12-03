<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 2) {
    die(json_encode([
        "erro" => "Acesso negado"
    ]));
}
?>
