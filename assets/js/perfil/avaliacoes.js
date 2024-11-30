document.addEventListener("DOMContentLoaded", () => {
const urlParams = new URLSearchParams(window.location.search);
const cuidadorId = urlParams.get("id");

//Preencher estrelas
function preencherEstrelas(media) {
    const estrelasDiv = document.getElementById('estrelas');
    estrelasDiv.innerHTML = '';

    for (let i = 1; i <= 5; i++) {
        const estrela = document.createElement('i');
        if (i <= Math.floor(media)) {
            estrela.classList.add('fa', 'fa-star', 'filled'); //Estrela cheia
        } else if (i - media < 1) {
            estrela.classList.add('fa', 'fa-star-half-alt', 'filled'); //Meia estrela
        } else {
            estrela.classList.add('fa', 'fa-star'); //Estrela vazia
        }
        estrelasDiv.appendChild(estrela);
    }
}

// Função para gerar estrelas em formato HTML
function gerarEstrelasHTML(media) {
    let estrelasHTML = '';

    for (let i = 1; i <= 5; i++) {
        if (i <= Math.floor(media)) {
            estrelasHTML += '<i class="fa fa-star filled"></i>'; // Estrela cheia
        } else if (i - media < 1) {
            estrelasHTML += '<i class="fa fa-star-half-alt filled"></i>'; // Meia estrela
        } else {
            estrelasHTML += '<i class="fa fa-star empty"></i>'; // Estrela vazia
        }
    }

    return estrelasHTML;
}

// Carregar comentários
fetch(`../../includes/get-comentarios.php?id=${cuidadorId}`)
    .then(response => response.json())
    .then(comentarios => {
        const comentariosDiv = document.getElementById('comentarios');
        comentariosDiv.innerHTML = '';

        comentarios.forEach(c => {
            const comentarioHtml = `
                <div class="comentario">
                    <div class="comentario-header">
                        <img src="${c.foto_perfil}" alt="Foto do tutor" class="avatar-tutor">
                        <p>${c.nome}</p>
                    </div>
                    <p><strong>${gerarEstrelasHTML(c.nota)}</strong></p>
                    <p>${c.comentario}</p>
                    <small>${new Date(c.data_avaliacao).toLocaleDateString()}</small>
                </div>
            `;
            comentariosDiv.innerHTML += comentarioHtml;
        });
    })
    .catch(error => console.error('Erro ao carregar comentários:', error));

 // Carregar avaliações
fetch(`../../includes/get-avaliacoes.php?id=${cuidadorId}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('media').innerText = `${data.media_rating.toFixed(1)}`;
        document.getElementById('total').innerText = `(${data.total_avaliacoes})`;
        preencherEstrelas(data.media_rating); 
    })
    .catch(error => console.error('Erro ao carregar as avaliações:', error));
});


function redirecionarConta() {
    
    Swal.fire({
        icon: "warning",
        title: "Perfil incompleto",
        text: "Para prosseguir, termine de preencher sua conta..",
    });
  }