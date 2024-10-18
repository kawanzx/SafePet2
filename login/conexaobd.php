<?php
$host = "localhost";
$user = "root";
$pass = "root";
$bd = "safepet";

$mysqli = new mysqli($host, $user, $pass, $bd);

if ($mysqli->connect_error) {
    die("Erro de conexão: " . $mysqli->connect_error);
}