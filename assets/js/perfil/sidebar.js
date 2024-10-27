/* Sidebar */
document.addEventListener('DOMContentLoaded', () => {
const sidebar = document.querySelector('.sidebar');

// Função para alternar a expansão da sidebar ao clicar no cabeçalho
function toggleSidebar() {
    sidebar.classList.toggle('sidebar-expandida');

    // Se a sidebar for recolhida, esconda todos os submenus
    if (!sidebar.classList.contains('sidebar-expandida')) {
        const submenus = sidebar.querySelectorAll('.submenu');
        submenus.forEach(submenu => {
            submenu.style.display = 'none'; // Esconde todos os submenus
        });
    }
}

// Adicionando o evento de clique ao cabeçalho da sidebar
const header = document.querySelector('.sidebar-header');
header.addEventListener('click', toggleSidebar);

// Adicionando o evento de clique aos ícones na sidebar
const icons = sidebar.querySelectorAll('nav ul li > a > span.material-symbols-outlined');
icons.forEach(icon => {
    icon.parentElement.addEventListener('click', function (event) {
        // Impede que a sidebar seja recolhida ao clicar no ícone
        event.stopPropagation();

        // Expandir a sidebar se estiver recolhida
        if (!sidebar.classList.contains('sidebar-expandida')) {
            sidebar.classList.add('sidebar-expandida');
        }
    });
});

// Função para fechar a sidebar ao clicar fora dela
document.addEventListener('click', function (event) {
    // Verifica se o clique foi fora da sidebar e se a sidebar está expandida
    if (!sidebar.contains(event.target) && sidebar.classList.contains('sidebar-expandida')) {
        sidebar.classList.remove('sidebar-expandida'); // Recolhe a sidebar

        // Esconde todos os submenus quando a sidebar é recolhida
        const submenus = sidebar.querySelectorAll('.submenu');
        submenus.forEach(submenu => {
            submenu.style.display = 'none';
        });
    }
});

// Função para mostrar o conteúdo
window.showContent = function(sectionId, element) {
    // Oculta todas as seções de conteúdo
    var sections = document.querySelectorAll('.content-section');
    sections.forEach(function (section) {
        section.classList.remove('active');
    });

    // Exibe a seção selecionada
    var activeSection = document.getElementById(sectionId);
    if (activeSection) {
        activeSection.classList.add('active');
    }

    // Remove a classe 'active' de todos os links da sidebar
    var links = document.querySelectorAll('.sidebar a');
    links.forEach(function (link) {
        link.classList.remove('active');
    });

    // Adiciona a classe 'active' ao link clicado
    if (element) {
        element.classList.add('active');
    }

    // Se o link clicado é parte do submenu, não recolhe a sidebar
    var isSubmenu = element.closest('ul.submenu');
    if (isSubmenu) {
        var subContentId = element.dataset.subContent;

        // Oculta todos os sub-conteúdos
        var subSections = document.querySelectorAll('#' + sectionId + ' > div');
        subSections.forEach(function (subSection) {
            subSection.style.display = 'none';
        });

        // Exibe o sub-conteúdo selecionado
        if (subContentId) {
            var activeSubSection = document.getElementById(subContentId);
            if (activeSubSection) {
                activeSubSection.style.display = 'block';
            }
        }
        return;
    }

    // Após a seleção do conteúdo, recolhe a sidebar se não for um submenu
    if (sidebar.classList.contains('sidebar-expandida')) {
        sidebar.classList.remove('sidebar-expandida');
    }
}

// Função para alternar a exibição do submenu
window.alternarSubmenu = function(event) {
    event.preventDefault();
    const submenu = event.currentTarget.nextElementSibling;

    if (submenu) {
        if (submenu.style.display === 'block') {
            submenu.style.display = 'none';
        } else {
            submenu.style.display = 'block';
        }
    }
}
});
