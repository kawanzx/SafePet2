document.querySelector("form[id='form-login']").addEventListener('submit', function(event) {
    event.preventDefault(); 

    var email = document.querySelector("input[name='email']").value;
    var senha = document.querySelector("input[name='senha']").value;
    var tipo_usuario = document.querySelector("select[name='tipo_usuario']").value;

    fetch('/auth/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            email: email,
            senha: senha,
            tipo_usuario: tipo_usuario
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
                window.location.href = tipo_usuario === 'tutor' ? '/views/tutor/buscar.php' : '/views/shared/agendamentos.php';
            }
        });
    })
    .catch(error => {
        console.error('Erro:', error);
        Swal.fire({
            icon: "error",
            title: "Erro!",
            text: "Ocorreu um problema ao tentar fazer login.",
        });
    });
});


