<?php
// Configuração da ligação à base de dados

// Faz com que os erros do mysqli sejam devolvidos como valores (false),
// em vez de lançarem exceções — assim o código pode continuar a usar
// "if ($stmt->execute()) { ... } else { ... }" para tratar erros.
mysqli_report(MYSQLI_REPORT_OFF);

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "biblioteca";

// Cria a ligação usando mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Define o charset para evitar problemas com acentuação (é, ã, ç, etc.)
$conn->set_charset("utf8mb4");

// Verifica se a ligação foi bem-sucedida
if ($conn->connect_error) {
    die("Ligação falhou: " . $conn->connect_error);
}
?>
