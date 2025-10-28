<?php
$host = "localhost";
$usuario = "root";
$banco = "cadastro";
$senha = "";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>