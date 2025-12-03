<?php
session_start();

if (!isset($_SESSION['id'])) {
    die(json_encode([
        "erro" => "Não autenticado"
    ]));
}
?>