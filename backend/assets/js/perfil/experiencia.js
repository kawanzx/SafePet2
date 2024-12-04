// Editar Experiencia
document.addEventListener('DOMContentLoaded', () => {
    const editButton = document.querySelector('.editar-experiencia');
    const saveButton = document.querySelector('.salvar-experiencia');
    const experienciaText = document.querySelector('.experienciaText');
    const experienciaInput = document.querySelector('.experienciaInput');
    
    editButton.addEventListener('click', function () {
        if (experienciaInput.style.display === 'none') {
            experienciaText.style.display = 'none';
            experienciaInput.style.display = 'block';
            saveButton.style.display = 'block';
            experienciaInput.value = experienciaText.textContent.trim();
        } else {
            experienciaText.textContent = experienciaInput.value.trim();
            experienciaText.style.display = 'block';
            experienciaInput.style.display = 'none';
            saveButton.style.display = 'none';
        }
    });
    
    });