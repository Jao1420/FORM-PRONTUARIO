// Elementos do DOM
const form = document.getElementById('formSaida');
const prontuarioInput = document.getElementById('prontuarioLeitor');
const materialsContainer = document.querySelector('.materials-grid');
const alertBox = document.getElementById('alert');
const tableBody = document.getElementById('registrosTableBody');
const filterBtnDatas = document.getElementById('filterBtnDatas');
const dataInicio = document.getElementById('dataInicio');
const dataFim = document.getElementById('dataFim');
const exportBtn = document.getElementById('exportBtn');

// Variável para armazenar dados do usuário
let usuarioAtual = null;

// Padrão dos materiais
const MATERIAIS = [
    { id: 'luva_borracha', nome: 'Luva de ponta de borracha' },
    { id: 'luva_antiestatica', nome: 'Luva antiestática' },
    { id: 'alcool_gel', nome: 'Álcool em gel' },
    { id: 'alcool_isopropilico', nome: 'Álcool isopropílico' },
    { id: 'fita_demarcacao', nome: 'Fita de demarcação' }
];

// Criar checkboxes de materiais dinâmicamente
function initMaterials() {
    MATERIAIS.forEach(material => {
        const materialItem = document.createElement('div');
        materialItem.classList.add('material-item');
        materialItem.innerHTML = `
            <input type="checkbox" id="${material.id}" name="materiais" value="${material.id}" data-nome="${material.nome}">
            <label for="${material.id}">${material.nome}</label>
            <div class="material-quantity">
                <span>Qtd:</span>
                <input type="number" min="1" value="1" class="qty-input" data-material="${material.id}">
            </div>
        `;
        materialsContainer.appendChild(materialItem);
    });
}

// Buscar usuário pelo prontuário do leitor
async function buscarUsuario(prontuario) {
    try {
        const response = await fetch(`api/buscar-usuario.php?prontuarioLeitor=${encodeURIComponent(prontuario)}`);
        const data = await response.json();

        if (data.sucesso) {
            usuarioAtual = data.usuario;
            showAlert(`✓ Usuário encontrado: ${data.usuario.nome}`, 'success');
            return true;
        } else {
            usuarioAtual = null;
            showAlert(`✗ ${data.mensagem}`, 'error');
            return false;
        }
    } catch (error) {
        console.error('Erro:', error);
        showAlert('✗ Erro ao buscar usuário.', 'error');
        usuarioAtual = null;
        return false;
    }
}

// Event listener para prontuário do leitor
prontuarioInput.addEventListener('blur', async () => {
    const prontuario = prontuarioInput.value.trim();
    if (prontuario) {
        await buscarUsuario(prontuario);
    }
});

// Mostrar alerta
function showAlert(message, type = 'success') {
    alertBox.textContent = message;
    alertBox.className = `alert ${type}`;
    
    if (type === 'success') {
        setTimeout(() => {
            alertBox.className = 'alert';
        }, 3000);
    }
}

// Enviar formulário
form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const prontuario = prontuarioInput.value.trim();
    const materiaisSelecionados = [];

    // Validar prontuário
    if (!prontuario) {
        showAlert('Por favor, passe o prontuário no leitor.', 'error');
        return;
    }

    // Validar se usuário foi encontrado
    if (!usuarioAtual) {
        showAlert('Usuário não encontrado. Por favor, valide o prontuário do leitor.', 'error');
        return;
    }

    // Coletar materiais selecionados
    document.querySelectorAll('input[name="materiais"]:checked').forEach(checkbox => {
        const materialId = checkbox.value;
        const quantidade = document.querySelector(`input[data-material="${materialId}"]`).value;
        
        materiaisSelecionados.push({
            material_id: materialId,
            material_nome: checkbox.dataset.nome,
            quantidade: parseInt(quantidade) || 1
        });
    });

    // Validar se selecionou algum material
    if (materiaisSelecionados.length === 0) {
        showAlert('Selecione pelo menos um material.', 'error');
        return;
    }

    // Enviar dados
    try {
        const response = await fetch('api/inserir-saida.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                prontuario_leitor: prontuario,
                nome_usuario: usuarioAtual.nome,
                materiais: materiaisSelecionados
            })
        });

        const result = await response.json();

        if (result.sucesso) {
            showAlert('Saída registrada com sucesso!', 'success');
            form.reset();
            usuarioAtual = null;
            carregarRegistros();
        } else {
            showAlert('Erro: ' + result.mensagem, 'error');
        }
    } catch (error) {
        console.error('Erro:', error);
        showAlert('Erro na conexão com o servidor.', 'error');
    }
});

// Carregar registros da tabela
async function carregarRegistros(dataInicioFiltro = null, dataFimFiltro = null) {
    try {
        let url = 'api/listar-registros.php';
        
        if (dataInicioFiltro && dataFimFiltro) {
            url += `?data_inicio=${dataInicioFiltro}&data_fim=${dataFimFiltro}`;
        }

        const response = await fetch(url);
        const data = await response.json();

        tableBody.innerHTML = '';

        if (data.registros && data.registros.length > 0) {
            data.registros.forEach(registro => {
                const row = document.createElement('tr');
                const dataHora = new Date(registro.data_hora).toLocaleString('pt-BR');
                const quantidade = registro.quantidade || 1;
                const nomeUsuario = registro.nome_usuario || 'N/A';
                const prontuario = registro.prontuario_leitor || 'N/A';

                row.innerHTML = `
                    <td>${nomeUsuario}</td>
                    <td>${prontuario}</td>
                    <td>${registro.material_nome || 'N/A'}</td>
                    <td>${quantidade}</td>
                    <td>${dataHora}</td>
                `;
                tableBody.appendChild(row);
            });
        } else {
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = '<td colspan="5" class="empty-state">Nenhum registro encontrado.</td>';
            tableBody.appendChild(emptyRow);
        }
    } catch (error) {
        console.error('Erro ao carregar registros:', error);
        showAlert('Erro ao carregar registros.', 'error');
    }
}

// Filtrar por data
filterBtnDatas.addEventListener('click', () => {
    const inicio = dataInicio.value;
    const fim = dataFim.value;

    if (!inicio || !fim) {
        showAlert('Preencha ambas as datas.', 'error');
        return;
    }

    carregarRegistros(inicio, fim);
});

// Exportar para Excel
exportBtn.addEventListener('click', () => {
    const inicio = dataInicio.value;
    const fim = dataFim.value;

    if (!inicio || !fim) {
        showAlert('Preencha ambas as datas para exportar.', 'error');
        return;
    }

    window.location.href = `api/exportar-excel.php?data_inicio=${inicio}&data_fim=${fim}`;
});

// Inicializar
document.addEventListener('DOMContentLoaded', () => {
    initMaterials();
    carregarRegistros();
});
