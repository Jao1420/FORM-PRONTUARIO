<?php
include 'conexao.php';

$nome = $_POST['nome'] ?? '';
$prontuario = $_POST['prontuario'] ?? '';
$prontuarioLeitor = $_POST['prontuarioLeitor'] ?? '';


if (empty($nome) || empty($prontuario) || empty($prontuarioLeitor)) {
    die("Erro: Campos obrigatórios ausentes.");
}

$stmt = $conn->prepare("INSERT INTO readerPront (nome, prontuario, readerProntcol) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nome, $prontuario, $prontuarioLeitor);

if ($stmt->execute()) {
    echo "sucesso";
} else {
    echo "Erro ao inserir: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>