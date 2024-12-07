document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("form").addEventListener('submit', function (event) {
        event.preventDefault();

        fetch('/backend/auth/enviar-recuperacao.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(new FormData(document.querySelector("form")))
        })
            .then(response => {
                console.log('Resposta completa:', response);
                if (!response.ok) {
                    throw new Error('Erro na rede: ' + response.statusText);
                }
                return response.text();
            })
            .then(text => {
                console.log('Corpo da resposta:', text);
                try {
                    const data = JSON.parse(text);
                    console.log('JSON Parseado:', data);
                    Swal.fire({
                        icon: data.sucesso ? "success" : "error",
                        title: data.sucesso ? "Sucesso!" : "Erro!",
                        text: data.mensagem,
                    }).then(() => {
                        if (data.sucesso) {
                            window.location.href = '/backend/index.php';
                        }
                    });
                } catch (error) {
                    console.error('Erro ao analisar JSON:', error);
                    Swal.fire({
                        icon: "error",
                        title: "Erro!",
                        text: "Ocorreu um problema ao tentar fazer o cadastro.",
                    });
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: "Ocorreu um problema ao tentar fazer o cadastro.",
                });
            });
    });
});