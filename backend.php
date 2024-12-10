<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root"; // ou o nome de usuário do seu banco de dados
$password = ""; // ou a senha do seu banco de dados
$dbname = "rifas_db"; // nome do banco de dados

// Criar a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die(json_encode(['error' => 'Erro de conexão: ' . $conn->connect_error]));
}

// Ler os dados do banco de dados
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM rifas";
    $result = $conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode([]);
    }
}

// Salvar ou atualizar uma linha no banco de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Verifica se os dados necessários estão presentes
    if (!isset($input['numero_rifa'])) {
        die(json_encode(['error' => 'Número da rifa é obrigatório']));
    }

    $numero_rifa = $input['numero_rifa'];
    $nome = $input['nome'];
    $telefone = $input['telefone'];
    $situacao_pagamento = $input['situacao_pagamento'];

    // Verifica se a rifa já existe
    $sql_check = "SELECT * FROM rifas WHERE numero_rifa = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("i", $numero_rifa);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Atualiza a linha existente
        $sql = "UPDATE rifas SET nome = ?, telefone = ?, situacao_pagamento = ? WHERE numero_rifa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nome, $telefone, $situacao_pagamento, $numero_rifa);
        $stmt->execute();
    } else {
        // Insere uma nova linha
        $sql = "INSERT INTO rifas (numero_rifa, nome, telefone, situacao_pagamento) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $numero_rifa, $nome, $telefone, $situacao_pagamento);
        $stmt->execute();
    }

    echo json_encode(['message' => 'Dados salvos com sucesso']);
}

// Limpar os dados de uma linha específica (mantendo o número da rifa)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['numero_rifa'])) {
        die(json_encode(['error' => 'Número da rifa é obrigatório']));
    }

    $numero_rifa = $input['numero_rifa'];
    $sql = "UPDATE rifas SET nome = NULL, telefone = NULL, situacao_pagamento = NULL WHERE numero_rifa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $numero_rifa);
    $stmt->execute();

    echo json_encode(['message' => 'Dados limpos com sucesso']);
}

// Fechar a conexão
$conn->close();
?>
