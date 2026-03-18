<?php
require_once '../conexao/conexao.php';

// Verificar se os parâmetros foram fornecidos
if (!isset($_GET['data_inicio']) || !isset($_GET['data_fim'])) {
    http_response_code(400);
    echo "Datas não fornecidas";
    exit;
}

$dataInicio = $_GET['data_inicio'];
$dataFim = $_GET['data_fim'];

// Validar datas
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataInicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataFim)) {
    http_response_code(400);
    echo "Formato de data inválido";
    exit;
}

// Adicionar um dia ao dataFim para incluir registros até o final do dia
$dataFimAjustada = date('Y-m-d', strtotime($dataFim . ' +1 day'));

// Consultar o banco
$sql = "SELECT nome_usuario, prontuario_leitor, material_nome, quantidade, data_hora 
        FROM saidas_materiais 
        WHERE DATE(data_hora) >= ? AND DATE(data_hora) < ?
        ORDER BY data_hora DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $dataInicio, $dataFimAjustada);
$stmt->execute();
$result = $stmt->get_result();

// Preparar dados para Excel
$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

// Criar arquivo CSV (Excel compatível)
$csvFileName = 'relatorio_estoque_' . date('Y-m-d_H-i-s') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $csvFileName . '"');

// Usar UTF-8 BOM para Excel abrir corretamente
echo "\xEF\xBB\xBF"; // BOM UTF-8

// Cabeçalhos
echo "Usuário,Prontuário,Material,Quantidade,Data/Hora\n";

// Dados
foreach ($registros as $registro) {
    $dataHora = date('d/m/Y H:i:s', strtotime($registro['data_hora']));
    $nomeUsuario = $registro['nome_usuario'];
    $prontuario = $registro['prontuario_leitor'];
    $material = $registro['material_nome'];
    $quantidade = $registro['quantidade'];
    
    // Escapar valores para CSV
    $nomeUsuario = str_replace('"', '""', $nomeUsuario);
    $prontuario = str_replace('"', '""', $prontuario);
    $material = str_replace('"', '""', $material);
    
    echo "\"$nomeUsuario\",\"$prontuario\",\"$material\",$quantidade,\"$dataHora\"\n";
}

$stmt->close();
$conn->close();
?>
