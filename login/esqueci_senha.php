<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci a Senha</title>
</head>
<body>
    <h1>Esqueci a Senha</h1>
    <p><a href="formlogin.html">VOLTAR</a></p>
    <form action="enviar_recuperacao.php" method="post">
        <label for="email">E-mail: </label>
        <input type="email" name="email" required> <br>
        <button type="submit">Enviar Link de Redefinição</button>
    </form>
</body>
</html>