<?php
header('Content-Type: application/json');
require_once '../conexao/conexao.php';

// Criar tabela se não existir
$sqlCreateTable = "CREATE TABLE IF NOT EXISTS saidas_materiais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prontuario_leitor VARCHAR(100) NOT NULL,
    material_id VARCHAR(50) NOT NULL,
    material_nome VARCHAR(255) NOT NULL,
    quantidade INT DEFAULT 1,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$conn->query($sqlCreateTable);

// Verificar filtros de data
$dataInicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : null;
$dataFim = isset($_GET['data_fim']) ? $_GET['data_fim'] : null;

$sql = "SELECT * FROM saidas_materiais WHERE 1=1";
$params = [];
$types = [];

if ($dataInicio && $dataFim) {
    // Adicionar um dia ao dataFim para incluir registros até o final do dia
    $dataFimAjustada = date('Y-m-d', strtotime($dataFim . ' +1 day'));
    
    $sql .= " AND DATE(data_hora) >= ? AND DATE(data_hora) < ?";
    $params = [$dataInicio, $dataFimAjustada];
    $types = "ss";
}

$sql .= " ORDER BY data_hora DESC";

if ($params) {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro na query']);
        exit;
    }
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

$registros = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $registros[] = $row;
    }
}

echo json_encode(['sucesso' => true, 'registros' => $registros]);

$conn->close();
?>
