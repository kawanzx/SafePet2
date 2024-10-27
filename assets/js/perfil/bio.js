// Editar Bio
document.addEventListener('DOMContentLoaded', () => {
const editButton = document.querySelector('.editar-bio');
const saveButton = document.querySelector('.salvar-bio');
const bioText = document.querySelector('.bioText');
const bioInput = document.querySelector('.bioInput');

editButton.addEventListener('click', function () {
    if (bioInput.style.display === 'none') {
        bioText.style.display = 'none';
        bioInput.style.display = 'block';
        saveButton.style.display = 'block';
        bioInput.value = bioText.textContent.trim();
    } else {
        bioText.textContent = bioInput.value.trim();
        bioText.style.display = 'block';
        bioInput.style.display = 'none';
        saveButton.style.display = 'none';
    }
});

});