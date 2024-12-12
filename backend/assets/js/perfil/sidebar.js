document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.sidebar');

    function toggleSidebar() {
        sidebar.classList.toggle('sidebar-expandida');
        // Fecha os submenus ao colapsar a sidebar
        if (!sidebar.classList.contains('sidebar-expandida')) {
            const submenus = sidebar.querySelectorAll('.submenu');
            submenus.forEach(submenu => {
                submenu.style.display = 'none';
            });
        }
    }

    // Adicionando o evento de clique ao cabeçalho da sidebar
    const header = document.querySelector('.sidebar-header');
    header.addEventListener('click', toggleSidebar);

    // Clique nos ícones para expandir a sidebar
    const icons = sidebar.querySelectorAll('nav ul li > a > span.material-symbols-outlined');
    icons.forEach(icon => {
        icon.parentElement.addEventListener('click', function (event) {
            event.stopPropagation();
            if (!sidebar.classList.contains('sidebar-expandida')) {
                sidebar.classList.add('sidebar-expandida');
            }
        });
    });

    // Função para fechar a sidebar ao clicar fora dela
    document.addEventListener('click', function (event) {
        if (!sidebar.contains(event.target) && sidebar.classList.contains('sidebar-expandida')) {
            sidebar.classList.remove('sidebar-expandida');
            const submenus = sidebar.querySelectorAll('.submenu');
            submenus.forEach(submenu => {
                submenu.style.display = 'none';
            });
        }
    });

    // Função para mostrar o conteúdo e fechar a sidebar
    window.showContent = function (sectionId, element) {
        const sections = document.querySelectorAll('.content-section');
        sections.forEach(function (section) {
            section.classList.remove('active');
            section.style.display = 'none';
        });

        const activeSection = document.getElementById(sectionId);
        if (activeSection) {
            activeSection.classList.add('active');
            activeSection.style.display = 'block';
        }

        const links = document.querySelectorAll('.sidebar a');
        links.forEach(function (link) {
            link.classList.remove('active');
        });

        if (element) {
            element.classList.add('active');
        }

        if (sidebar.classList.contains('sidebar-expandida')) {
            sidebar.classList.remove('sidebar-expandida');
        }

        const submenus = sidebar.querySelectorAll('.submenu');
        submenus.forEach(submenu => {
            submenu.style.display = 'none';
        });

        const isSubmenu = element.closest('ul.submenu');
        if (!isSubmenu) {
            sidebar.classList.remove('sidebar-expandida');
        } else {
            const subContentId = element.dataset.subContent;
            const subSections = document.querySelectorAll('#' + sectionId + ' > div');
            subSections.forEach(function (subSection) {
                subSection.style.display = 'none';
            });

            if (subContentId) {
                var activeSubSection = document.getElementById(subContentId);
                if (activeSubSection) {
                    activeSubSection.style.display = 'block';
                }
            }
        }
    };

    window.alternarSubmenu = function (event) {
        event.preventDefault();
        const submenu = event.currentTarget.nextElementSibling;
        if (submenu) {
            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
            } else {
                submenu.style.display = 'block';
            }
        }
    };
});
