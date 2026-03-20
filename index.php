<?php require_once 'conexao/conexao.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Saída de Estoque</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <!-- CABEÇALHO -->
        <div class="header">
            <h1>Gerenciamento de Saída de Estoque</h1>
            <p>Registre os materiais que estão sendo retirados do estoque</p>
        </div>

        <!-- ALERTA -->
        <div id="alert" class="alert"></div>

        <!-- FORMULÁRIO DE SAÍDA -->
        <div class="form-section">
            <form id="formSaida">
                <div class="form-group">
                    <label for="prontuarioLeitor">🔖 Passe o Prontuário no Leitor:</label>
                    <input 
                        type="text" 
                        id="prontuarioLeitor" 
                        name="prontuarioLeitor" 
                        placeholder="Aproxime o prontuário do leitor..." 
                        required 
                        autofocus>
                </div>

                <!-- SELEÇÃO DE MATERIAIS -->
                <div class="materials-section">
                    <h3>Selecione o(s) Material(is) Retirado(s):</h3>
                    <div class="materials-grid" id="materialsContainer"></div>
                </div>

                <!-- BOTÕES -->
                <div class="button-group">
                    <button type="submit" class="btn-submit">✓ Registrar Saída</button>
                    <button type="reset" class="btn-reset">Limpar</button>
                </div>
            </form>
        </div>

        <!-- TABELA DE REGISTROS -->
        <div class="table-section">
            <div class="table-header">
                <h2>Histórico de Saídas</h2>
                <div class="filter-group">
                    <input 
                        type="date" 
                        id="dataInicio" 
                        placeholder="Data início">
                    <input 
                        type="date" 
                        id="dataFim" 
                        placeholder="Data fim">
                    <button id="filterBtnDatas" class="btn-export">Filtrar</button>
                    <button id="exportBtn" class="btn-export">Exportar Excel</button>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Prontuário</th>
                        <th>Total de Retiradas</th>
                        <th>Última Retirada</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody id="registrosTableBody">
                    <tr>
                        <td colspan="4" class="empty-state">Carregando registros...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL DE DETALHES -->
    <div id="modalDetalhes" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detalhes de Retirada</h2>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <div id="modalInfo"></div>
            </div>
            <div class="modal-footer">
                <button class="btn-reset close-modal">Fechar</button>
            </div>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>