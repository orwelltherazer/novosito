/**
 * NovoSito - Thème par défaut - JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des fonctionnalités du thème

    // Smooth scroll pour les ancres
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Lightbox simple pour les images de galerie
    const galleryImages = document.querySelectorAll('.gallery-item img');
    galleryImages.forEach(img => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', function() {
            openLightbox(this.src, this.alt);
        });
    });
});

/**
 * Ouvre une lightbox pour afficher une image
 */
function openLightbox(src, alt) {
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.innerHTML = `
        <div class="lightbox-overlay">
            <span class="lightbox-close">&times;</span>
            <img src="${src}" alt="${alt}" class="lightbox-image">
        </div>
    `;

    // Styles pour la lightbox
    const style = document.createElement('style');
    style.textContent = `
        .lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
        }
        .lightbox-overlay {
            position: relative;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10000;
        }
        .lightbox-close:hover {
            opacity: 0.7;
        }
        .lightbox-image {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }
    `;

    document.head.appendChild(style);
    document.body.appendChild(lightbox);

    // Fermer la lightbox
    lightbox.querySelector('.lightbox-close').addEventListener('click', function() {
        document.body.removeChild(lightbox);
        document.head.removeChild(style);
    });

    lightbox.querySelector('.lightbox-overlay').addEventListener('click', function(e) {
        if (e.target === this) {
            document.body.removeChild(lightbox);
            document.head.removeChild(style);
        }
    });
}
