
/* NavBar confirmação de logout*/
document.getElementById('logoutLink').addEventListener('click', function (event) {
    event.preventDefault();

    var confirmation = window.confirm("Você realmente deseja sair?");
    if (confirmation) {
        window.location.href = "/login/logout.php";
    }
});


/* Sidebar */
// Função para exibir o conteúdo com base no ID da seção
function showContent(sectionId, element) {
    // Oculta todas as seções de conteúdo
    var sections = document.querySelectorAll('.content-section');
    sections.forEach(function (section) {
        section.classList.remove('active');
    });

    // Exibe a seção selecionada
    var activeSection = document.getElementById(sectionId);
    activeSection.classList.add('active');

    // Exibe a seção selecionada
    var activeSection = document.getElementById(sectionId);
    activeSection.classList.add('active');

    // Remove a classe 'active' de todos os links da sidebar
    var links = document.querySelectorAll('.sidebar a');
    links.forEach(function (link) {
        link.classList.remove('active');
    });

    // Adiciona a classe 'active' ao link clicado
    element.classList.add('active');
}


// Editar Pet

document.querySelectorAll('.editar-btn').forEach(button => {
    button.addEventListener('click', function () {
        const petDiv = this.closest('.pet');

        // Ocultar os textos e exibir os inputs
        petDiv.querySelectorAll('.nomePetText, .especieText, .racaText, .idadeText, .sexoText, .pesoText, .castradoText, .descricaoText').forEach(text => {
            text.style.display = 'none';
        });
        petDiv.querySelectorAll('.nomePetInput, .especieInput, .racaInput, .idadeInput, .sexoInput, .pesoInput, .castradoInput, .descricaoInput').forEach(input => {
            input.style.display = 'block';
        });

        // Alternar os botões
        this.style.display = 'none';
        petDiv.querySelector('.salvar-btn').style.display = 'inline-block';
    });
});

document.querySelectorAll('.salvar-btn').forEach(button => {
    button.addEventListener('click', function () {
        const petDiv = this.closest('.pet');
        const petId = petDiv.getAttribute('data-pet-id'); // Supondo que você tenha um atributo data-id com o ID do pet

        // Obter os valores dos inputs
        const nome = petDiv.querySelector('.nomePetInput').value;
        const especie = petDiv.querySelector('.especieInput').value;
        const raca = petDiv.querySelector('.racaInput').value;
        const idade = petDiv.querySelector('.idadeInput').value;
        const sexo = petDiv.querySelector('.sexoInput').value;
        const peso = petDiv.querySelector('.pesoInput').value;
        const castrado = petDiv.querySelector('.castradoInput').value;
        const descricao = petDiv.querySelector('.descricaoInput').value;
        const fotoInput = petDiv.querySelector('input[type="file"]');
        const fotoAtual = petDiv.querySelector('.fotoPetAtual').value;


        // Verificar os valores no console
        console.log({
            petId: petId,
            nome: nome,
            especie: especie,
            raca: raca,
            idade: idade,
            sexo: sexo,
            peso: peso,
            castrado: castrado,
            descricao: descricao
        });

        // Enviar os dados via AJAX
        const formData = new FormData();
        formData.append('petId', petId);
        formData.append('nome', nome);
        formData.append('especie', especie);
        formData.append('raca', raca);
        formData.append('idade', idade);
        formData.append('sexo', sexo);
        formData.append('peso', peso);
        formData.append('castrado', castrado);
        formData.append('descricao', descricao);

        if (fotoInput && fotoInput.files.length > 0) {
            formData.append('foto', fotoInput.files[0]);
        } else {
            // Se não houver uma nova foto, adicione o valor da foto atual (se existir)
            formData.append('foto_atual', fotoAtual);
        }

        fetch('editar-pet.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                if (data.includes("sucesso")) {
                    // Atualizar os textos com os novos valores
                    petDiv.querySelector('.nomePetText').textContent = nome;
                    petDiv.querySelector('.especieText').textContent = especie === 'cachorro' ? 'Cachorro' : 'Gato';
                    petDiv.querySelector('.racaText').textContent = raca;
                    petDiv.querySelector('.idadeText').textContent = idade;
                    petDiv.querySelector('.sexoText').textContent = sexo;
                    petDiv.querySelector('.pesoText').textContent = peso;
                    petDiv.querySelector('.castradoText').textContent = castrado;
                    petDiv.querySelector('.descricaoText').textContent = descricao;

                    // Atualizar a imagem caso uma nova foto tenha sido enviada
                    if (fotoInput && fotoInput.files.length > 0) {
                        const newImageUrl = URL.createObjectURL(fotoInput.files[0]);
                        petDiv.querySelector('.fotoPet img').src = newImageUrl;
                    }

                    // Ocultar os inputs e exibir os textos
                    petDiv.querySelectorAll('.nomePetText, .especieText, .racaText, .idadeText, .sexoText, .pesoText, .castradoText, .descricaoText').forEach(text => {
                        text.style.display = 'inline';
                    });
                    petDiv.querySelectorAll('.nomePetInput, .especieInput, .racaInput, .idadeInput, .sexoInput, .pesoInput, .castradoInput, .descricaoInput, .fotoPetInput').forEach(input => {
                        input.style.display = 'none';
                    });

                    // Alternar os botões
                    petDiv.querySelector('.salvar-btn').style.display = 'none';
                    petDiv.querySelector('.editar-btn').style.display = 'inline-block';
                } else {
                    alert('Erro ao atualizar os dados: ' + data);
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                alert('Erro na comunicação com o servidor.');
            });
    });
});

document.querySelectorAll('.fotoPetImg').forEach(image => {
    image.addEventListener('click', function () {
        const petDiv = this.closest('.pet');
        const fileInput = petDiv.querySelector('.fotoPetInput');
        fileInput.click(); // Simula o clique no input de arquivo
    });
});
