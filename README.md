A aplicação consiste em um **formulário (forms) simples** para entrada e consulta de dados, realizando a persistência e recuperação das informações em um banco de dados estruturado.

## Funcionalidades
* **Interface de Formulário:** Entrada amigável para associação de prontuários.
* **Integração com Banco de Dados:** Armazenamento seguro e persistente.
* **Leitura de Identificação:** Lógica preparada para vincular IDs de chips aos registros internos.

## Boas Práticas
Para garantir a integridade dos dados e a segurança das credenciais, o projeto utiliza:
* **`.env`**: Gerenciamento de variáveis de ambiente (como senhas de banco de dados e chaves de API), mantendo dados sensíveis fora do código-fonte.
* **`.gitignore`**: Configurado para impedir que arquivos de configuração local, dependências pesadas.

##  Tecnologias Utilizadas
* **Frontend:**: HTML/CSS/JS
* **Backend:**: PHP
* **Banco de Dados:**: MySQL
* **Segurança:** Dotenv (Variáveis de Ambiente)

## 📁 Estrutura do Projeto

```text
READER/
├── conexao/
│   └── conexao.php          # Configuração e lógica de conexão com o BD
├── insert/
│   └── inserirPront.php     # Lógica para processar e inserir dados do prontuário
├── .env                     # Variáveis de ambiente (credenciais sensíveis)
├── .gitignore               # Define arquivos que não serão enviados ao GitHub
├── composer.json            # Gestão de dependências (phpdotenv)
├── index.php                # Interface principal (formulário simples)
└── vendor/                  # Pastas de bibliotecas instaladas pelo Composer
