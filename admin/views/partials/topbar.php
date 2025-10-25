<div class="topbar">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <!-- Bouton menu mobile (pour plus tard) -->
        </div>

        <div class="d-flex align-items-center gap-3">
            <a href="/" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-eye me-1"></i>
                Voir le site
            </a>

            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user me-1"></i>
                    Admin
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="/admin/profile"><i class="fas fa-user me-2"></i>Profil</a></li>
                    <li><a class="dropdown-item" href="/admin/settings"><i class="fas fa-cog me-2"></i>Paramètres</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/admin/logout"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
