<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci a Senha</title>
    <link rel="stylesheet" href="style-login.css">
</head>
<body>
    <div class="main">
        <div class="esquerda">
            <h1>Recupere sua conta</h1>
            <img src="assets/cachorro.svg" class="imagem-esquerda" alt="Cachorro">
        </div>
        <div class="direita">
            <div class="card-login">
                <div class="container">
                    <a href="formlogin.html"><img src="assets/seta-voltar.svg"></a>
                    <h1>Insira seu E-mail</h1>
                </div>
                <form action="enviar_recuperacao.php" method="post">
                    <div class="textfield">
                    <label for="email">E-mail: </label>
                    <input type="email" name="email" placeholder="E-mail" required> <br>
                    </div>
                    <button type="submit" class="btn-login">Enviar Link de <br> Redefinição</button>
                </form>
            </div>        

        </div>

    </div>
</body>
</html>