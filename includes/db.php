<?php

$mysqli = new mysqli("localhost", "root", "root", "safepet");

if ($mysqli->connect_error) {
    die("Erro de conexão: " . $mysqli->connect_error);
}