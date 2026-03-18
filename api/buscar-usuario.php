<?php
header('Content-Type: application/json');
require_once '../conexao/conexao.php';

if (!isset($_GET['prontuarioLeitor'])) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Prontuário não fornecido']);
    exit;
}

$prontuarioLeitor = trim($_GET['prontuarioLeitor']);

if (empty($prontuarioLeitor)) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Prontuário vazio']);
    exit;
}

// Buscar usuário na tabela readerPront
$stmt = $conn->prepare("SELECT nome, prontuario, readerProntcol FROM readerPront WHERE readerProntcol = ?");
$stmt->bind_param("s", $prontuarioLeitor);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    echo json_encode([
        'sucesso' => true,
        'usuario' => $usuario
    ]);
} else {
    http_response_code(404);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Usuário não encontrado. Por favor, cadastre o usuário primeiro.'
    ]);
}

$stmt->close();
$conn->close();
?>
