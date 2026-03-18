<?php
header('Content-Type: application/json');
require_once '../conexao/conexao.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['prontuario_leitor'], $input['materiais'])) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados inválidos']);
    exit;
}

$prontuario_leitor = trim($input['prontuario_leitor']);
$nome_usuario = isset($input['nome_usuario']) ? trim($input['nome_usuario']) : '';
$materiais = $input['materiais'];

if (empty($prontuario_leitor) || empty($materiais)) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Prontuário e materiais são obrigatórios']);
    exit;
}

// Criar tabela se não existir
$sqlCreateTable = "CREATE TABLE IF NOT EXISTS saidas_materiais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prontuario_leitor VARCHAR(100) NOT NULL,
    nome_usuario VARCHAR(255),
    material_id VARCHAR(50) NOT NULL,
    material_nome VARCHAR(255) NOT NULL,
    quantidade INT DEFAULT 1,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!$conn->query($sqlCreateTable)) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao criar tabela']);
    exit;
}

// Inserir cada material selecionado
$todosInscritos = true;
$erros = [];

foreach ($materiais as $material) {
    $material_id = trim($material['material_id']);
    $material_nome = trim($material['material_nome']);
    $quantidade = intval($material['quantidade']) ?? 1;

    $stmt = $conn->prepare("INSERT INTO saidas_materiais (prontuario_leitor, nome_usuario, material_id, material_nome, quantidade) VALUES (?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        $todosInscritos = false;
        $erros[] = "Erro na preparação: " . $conn->error;
        continue;
    }

    $stmt->bind_param("ssssi", $prontuario_leitor, $nome_usuario, $material_id, $material_nome, $quantidade);

    if (!$stmt->execute()) {
        $todosInscritos = false;
        $erros[] = "Erro ao inserir " . $material_nome . ": " . $stmt->error;
    }

    $stmt->close();
}

if ($todosInscritos) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Saída registrada com sucesso']);
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Alguns registros falharam: ' . implode(', ', $erros)]);
}

$conn->close();
?>
