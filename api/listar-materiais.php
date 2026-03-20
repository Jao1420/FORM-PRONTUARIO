<?php
header('Content-Type: application/json');
require_once '../conexao/conexao.php';

try {
    $sql = "SELECT id, nome_material as nome FROM materiais ORDER BY nome_material ASC";
    $result = $conn->query($sql);

    if (!$result) {
        http_response_code(500);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Erro ao buscar materiais'
        ]);
        exit;
    }

    $materiais = [];
    while ($row = $result->fetch_assoc()) {
        $materiais[] = [
            'id' => $row['id'],
            'nome' => $row['nome']
        ];
    }

    echo json_encode([
        'sucesso' => true,
        'materiais' => $materiais
    ]);

    $result->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao processar requisição'
    ]);
}

$conn->close();
?>
