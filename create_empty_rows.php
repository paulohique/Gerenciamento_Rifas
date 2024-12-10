<?php
$servername = "localhost";
$username = "root"; // Substitua pelo seu usuário do BD
$password = ""; // Substitua pela sua senha do BD
$dbname = "rifas_db"; // Substitua pelo nome do seu banco de dados

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Nome da tabela
$tableName = "rifas";

// Cria 100 linhas vazias na tabela
for ($i = 1; $i <= 100; $i++) {
    $sql = "INSERT INTO $tableName (numero_rifa, nome, telefone, situacao_pagamento) 
            VALUES ($i, '', '', '')";

    if ($conn->query($sql) === TRUE) {
        echo "Linha $i criada com sucesso.<br>";
    } else {
        echo "Erro ao criar a linha $i: " . $conn->error . "<br>";
    }
}

// Fecha a conexão
$conn->close();
?>
