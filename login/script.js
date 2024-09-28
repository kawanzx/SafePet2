function validarNome(nome) {
    var regex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/;
    return regex.test(nome);
}

function validarEndereco(endereco) {
    var regex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+,\s*\d+$/;
    return regex.test(endereco);
}

function validarFormulario() {
    var nome = document.getElementsByName("nome_completo")[0].value;
    var endereco = document.getElementsByName("endereco")[0].value;

    if (!validarNome(nome)) {
        alert("O nome deve conter apenas letras e espaços.");
        return false;
    }

    if (!validarEndereco(endereco)) {
        alert("O endereço deve estar no formato: Rua, Número (ex: Avenida Brasil, 123).");
        return false;
    }

    return true;
}
