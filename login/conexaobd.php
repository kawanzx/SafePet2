<?php
$host = "localhost";
$user = "root";
$pass = "";
$bd = "safepet";

$mysqli = new mysqli($host, $user, $pass, $bd);

if ($mysqli->connect_error) {
    die("Erro de conexão: " . $mysqli->connect_error);
}