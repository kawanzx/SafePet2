document.querySelector("form[id='form-cadastro']").addEventListener('submit', function(event) {
    event.preventDefault(); 

    var email = document.querySelector("input[name='email']").value;
    var senha = document.querySelector("input[name='senha']").value;
    var tipo_usuario = document.querySelector("select[name='tipo_usuario']").value;
    var nome_completo = document.querySelector("input[name='nome_completo']").value;
    var telefone = document.querySelector("input[name='telefone']").value;
    var confirmar_senha = document.querySelector("input[name='confirmar_senha']").value;
    var data_nascimento = document.querySelector("input[name='data_nascimento']").value;
    var cpf = document.querySelector("input[name='cpf']").value;

    fetch('/auth/register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            email: email,
            senha: senha,
            tipo_usuario: tipo_usuario,
            nome_completo: nome_completo,
            telefone: telefone,
            confirmar_senha: confirmar_senha,
            data_nascimento: data_nascimento,
            cpf: cpf
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na rede: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        Swal.fire({
            icon: data.sucesso ? "success" : "error",
            title: data.sucesso ? "Sucesso!" : "Erro!",
            text: data.mensagem,
        }).then(() => {
            if (data.sucesso) {
                window.location.href = '/auth/login.html';
            }
        });
    })
    .catch(error => {
        console.error('Erro:', error);
        Swal.fire({
            icon: "error",
            title: "Erro!",
            text: "Ocorreu um problema ao tentar fazer o cadastro.",
        });
    });
});