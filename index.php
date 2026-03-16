<?php require_once 'conexao.php'; 
// require_once 'dados.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de prontuário</title>
    <style>
        body{
            background-image: linear-gradient(to bottom, #f99a07, #f89405, #f78e04, #f58704, #f48105);;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .inputs{
            width: 300px;
            height: 30px;
            font-size: 16px;
            border-radius: 5px;
        }
        .form{
            background-color: rgba(16, 45, 207, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            align-items: center;
            display: flex;
            flex-direction: column;
        }
        .textos{
            color: white;
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        #cadastrar{
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Cadastro de prontuário</h1>
    <form class="form" id="formProntuario" action="inserirPront.php" method="POST">
        <label class="textos" for="nomeInput">Nome do colaborador:</label><br>
        <input class="inputs" type="text" id="nome" name="nome" required><br><br>

        <label class="textos" for="prontuarioInput">Prontuário da Visteon:</label><br>
        <input class="inputs" type="text" id="prontuario" name="prontuario" required><br><br>

        <label class="textos" for="prontuarioLeitorInput">Passe o prontuário no leitor :</label><br>
        <input class="inputs" type="text" id="prontuarioLeitor" name="prontuarioLeitor" required><br><br>

        <input class="inputs" id="cadastrar" type="submit" value="Cadastrar">
    </form>
</body>
<script>
    document.getElementById('formProntuario').addEventListener('submit', function(e) {
        e.preventDefault(); // isso impede a mudança de página!

        const formData = new FormData(this);

        // Enviam PHP via AJAX (fetch)
        fetch('inserirPront.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text()) // resposta do PHP
        .then(data => {
            if (data.trim() === "sucesso") {
                alert('Prontuário cadastrado com sucesso!');
                this.reset(); // Limpa os campos
            } else {
                alert('Erro: ' + data);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro na conexão com o servidor.');
        });
    });
</script>

</html>