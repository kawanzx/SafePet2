document.addEventListener("DOMContentLoaded", function () {
    //Validar telefone
    document.querySelector("#form-validacao").addEventListener('submit', function (event) {
        event.preventDefault();

        var codigo = document.querySelector("input[name='codigo']").value;
        var tipoUsuarioElement = document.getElementById("tipo_usuario");
        var tipo_usuario = tipoUsuarioElement.textContent.trim();
        console.log(tipo_usuario);

        fetch('/backend/auth/validar-codigo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                codigo: codigo
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    Swal.fire({
                        icon: "success",
                        title: "Código validado!",
                        text: data.mensagem,
                    }).then(() => {
                        window.location.href = tipo_usuario === 'tutor' ? '/backend/views/tutor/buscar.php' : '/backend/views/shared/agendamentos.php';
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erro!",
                        text: data.mensagem,
                    });
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: "Ocorreu um problema ao validar o código.",
                });
            });
    });

    document.getElementById('btn-reenviar-codigo').addEventListener('click', function () {
        fetch('/backend/auth/reenviar-codigo.php')
            .then(response => response.json())
            .then(data => {
                const mensagem = document.getElementById('mensagem-reenvio');
                if (data.sucesso) {
                    mensagem.style.color = 'green';
                    mensagem.innerText = 'Código reenviado com sucesso!';
                } else {
                    mensagem.style.color = 'red';
                    mensagem.innerText = 'Erro ao reenviar o código. Tente novamente.';
                }
                mensagem.style.display = 'block';
            })
            .catch(error => {
                console.error('Erro ao reenviar o código:', error);
                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: "Ocorreu um erro. Tente novamente mais tarde.",
                });
            });
    });

    document.getElementById('btn-trocar-telefone').addEventListener('click', function () {
        document.getElementById('trocar-telefone-container').style.display = 'block';
    });

    document.getElementById('confirmar-troca').addEventListener('click', function () {
        const novoTelefone = document.getElementById('novo-telefone').value;
        fetch('/backend/auth/trocar-telefone.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ novo_telefone: novoTelefone })
        })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    Swal.fire({
                        icon: "success",
                        title: "Sucesso!",
                        text: data.mensagem,
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erro!",
                        text: data.mensagem,
                    });
                }
            })
            .catch(error => {
                console.error('Erro ao trocar o telefone:', error);
                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: "Erro ao trocar o número. Tente novamente.",
                });
            });
    });

});