//Validação do login
function validarNome(nome) {
    var regex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]{2,}$/;
    return regex.test(nome);
}

function validarEndereco(endereco) {
    var regex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+,\s*\d+$/;
    return regex.test(endereco);
}

function validarEmail(email) {
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validarCPF(cpf) {
    var regex = /^\d{11}$/;
    return regex.test(cpf);
}

function validarTelefone(telefone) {
    var regex = /^\d{10,11}$/;
    return regex.test(telefone);
}

function validarSenha(senha) {
    var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{6,}$/;
    return regex.test(senha);
}


function validarFormulario() {
    var nome = document.getElementsByName("nome_completo")[0].value;
    var endereco = document.getElementsByName("endereco")[0].value;
    var email = document.getElementsByName("email")[0].value;
    var cpf = document.getElementsByName("cpf")[0].value;
    var telefone = document.getElementsByName("telefone")[0].value;
    var senha = document.getElementsByName("senha")[0].value;
    var confirmar_senha = document.getElementsByName("confirmar_senha")[0].value;

    if (!validarNome(nome)) {
        alert("O nome deve conter mais de 2 dígitos e apenas letras.");
        return false;
    }

    if (!validarEndereco(endereco)) {
        alert("O endereço deve estar no formato: Rua, Número (ex: Avenida Brasil, 123).");
        return false;
    }

    if (!validarEmail(email)) {
        alert("E-mail inválido.");
        return false;
    }
    if (!validarCPF(cpf)) {
        alert ('CPF inválido. Deve conter 11 dígitos.');
        return false;
    }
    if (!validarTelefone(telefone)) {
        alert("Telefone inválido. Deve conter 10 ou 11 dígitos.");
        return false;
    }
    if (!validarSenha(senha)) {
        alert("A senha deve conter pelo menos 6 caracteres, incluindo letras maiúsculas, minúsculas, números e caracteres especiais.");
        return false;
    }
    if (senha !== confirmar_senha) {
        alert("As senhas não coincidem.");
        return false;
    }

    return true;
}


