/**
 * NovoSito Admin - JavaScript
 */

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('NovoSito Admin initialisé');

    // Gestion du menu actif
    highlightActiveMenu();

    // Auto-fermeture des alertes
    autoCloseAlerts();
});

/**
 * Highlight le menu actif
 */
function highlightActiveMenu() {
    const currentPath = window.location.pathname;
    const menuItems = document.querySelectorAll('.sidebar-item');

    menuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && currentPath.startsWith(href) && href !== '/') {
            item.classList.add('active');
        } else if (href === '/admin' && currentPath === '/admin') {
            item.classList.add('active');
        }
    });
}

/**
 * Auto-fermeture des alertes après 5 secondes
 */
function autoCloseAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
}

/**
 * Affiche une notification toast
 */
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();

    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    toastContainer.appendChild(toast);

    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

/**
 * Crée le conteneur de toasts
 */
function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

/**
 * Confirme une action
 */
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

/**
 * Effectue une requête AJAX
 */
async function ajaxRequest(url, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    if (data) {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(url, options);
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Erreur AJAX:', error);
        return { success: false, message: 'Erreur réseau' };
    }
}

/**
 * Gestion de l'upload de fichiers
 */
function handleFileUpload(fileInput, callback) {
    const files = fileInput.files;

    if (files.length === 0) {
        return;
    }

    const formData = new FormData();
    formData.append('file', files[0]);

    fetch('/api/media/upload', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && callback) {
            callback(data.media);
        } else {
            showToast(data.message || 'Erreur lors de l\'upload', 'danger');
        }
    })
    .catch(error => {
        console.error('Erreur upload:', error);
        showToast('Erreur lors de l\'upload', 'danger');
    });
}

/**
 * Ouvre la bibliothèque de médias
 */
function openMediaLibrary(callback) {
    // TODO: Implémenter un modal pour la sélection de médias
    console.log('Ouverture de la bibliothèque de médias');
}

/**
 * Génère un slug à partir d'un titre
 */
function generateSlug(text) {
    return text
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}
