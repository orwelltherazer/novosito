<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer <?php echo htmlspecialchars($page['title']); ?> - NovoSito Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" href="/admin/assets/css/admin.css">
    <style>
        .module-item {
            transition: all 0.3s ease;
        }
        .module-item.dragging {
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-content">
        <?php include __DIR__ . '/../partials/topbar.php'; ?>

        <div class="content-wrapper p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Éditer la page</h1>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" onclick="previewPage()">
                        <i class="fas fa-eye me-2"></i>Prévisualiser
                    </button>
                    <a href="/admin/pages" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Informations de la page -->
                <div class="col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Informations</h5>
                        </div>
                        <div class="card-body">
                            <form id="page-form">
                                <input type="hidden" name="id" value="<?php echo $page['id']; ?>">

                                <div class="mb-3">
                                    <label class="form-label">Titre *</label>
                                    <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($page['title']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Slug</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($page['slug']); ?>" disabled>
                                    <small class="text-muted">Généré automatiquement</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Titre SEO</label>
                                    <input type="text" class="form-control" name="meta_title" value="<?php echo htmlspecialchars($page['meta_title'] ?? ''); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description SEO</label>
                                    <textarea class="form-control" name="meta_description" rows="3"><?php echo htmlspecialchars($page['meta_description'] ?? ''); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Statut</label>
                                    <select class="form-select" name="status">
                                        <option value="draft" <?php echo $page['status'] === 'draft' ? 'selected' : ''; ?>>Brouillon</option>
                                        <option value="published" <?php echo $page['status'] === 'published' ? 'selected' : ''; ?>>Publié</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_homepage" <?php echo $page['is_homepage'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label">Page d'accueil</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i>Sauvegarder
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Éditeur de modules -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Modules de contenu</h5>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#moduleModal">
                                <i class="fas fa-plus me-1"></i>Ajouter un module
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="modules-container" class="modules-container">
                                <?php if (empty($modules)): ?>
                                    <div class="text-center text-muted py-5">
                                        <i class="fas fa-cubes fa-3x mb-3"></i>
                                        <p>Aucun module. Cliquez sur "Ajouter un module" pour commencer.</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($modules as $moduleData): ?>
                                        <?php
                                        $moduleClass = 'Modules\\' . ucfirst($moduleData['type']) . '\\' . ucfirst($moduleData['type']) . 'Module';
                                        if (class_exists($moduleClass)) {
                                            $module = new $moduleClass([
                                                'id' => $moduleData['id'],
                                                'content' => json_decode($moduleData['content'], true),
                                                'settings' => json_decode($moduleData['settings'], true)
                                            ]);
                                        ?>
                                            <div class="module-item" data-module-id="<?php echo $moduleData['id']; ?>" draggable="true">
                                                <div class="module-header">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-grip-vertical text-muted me-3" style="cursor: move;"></i>
                                                        <strong><?php echo ucfirst($moduleData['type']); ?></strong>
                                                    </div>
                                                    <div class="module-actions">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editModule(<?php echo $moduleData['id']; ?>)">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteModule(<?php echo $moduleData['id']; ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="module-preview mt-2">
                                                    <?php echo $module->render(); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour ajouter un module -->
    <div class="modal fade" id="moduleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un module</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="module-selector">
                        <?php foreach ($availableModules as $type => $config): ?>
                            <?php if ($config['enabled']): ?>
                                <div class="module-option" onclick="addModule('<?php echo $type; ?>')">
                                    <i class="<?php echo $config['icon']; ?>"></i>
                                    <h6><?php echo $config['name']; ?></h6>
                                    <small class="text-muted"><?php echo $config['description']; ?></small>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/admin/assets/js/admin.js"></script>
    <script>
        const pageId = <?php echo $page['id']; ?>;

        // Sauvegarde de la page
        document.getElementById('page-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            fetch('/admin/pages/' + pageId, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showToast('Page sauvegardée', 'success');
                } else {
                    showToast('Erreur lors de la sauvegarde', 'danger');
                }
            });
        });

        // Ajouter un module
        function addModule(type) {
            fetch('/api/modules', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    type: type,
                    page_id: pageId,
                    content: {},
                    settings: {}
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    location.reload();
                } else {
                    showToast('Erreur lors de l\'ajout du module', 'danger');
                }
            });

            bootstrap.Modal.getInstance(document.getElementById('moduleModal')).hide();
        }

        // Éditer un module
        function editModule(moduleId) {
            // TODO: Ouvrir un modal d'édition
            alert('Édition du module ' + moduleId + ' - À implémenter');
        }

        // Supprimer un module
        function deleteModule(moduleId) {
            if (confirm('Supprimer ce module ?')) {
                fetch('/api/modules/' + moduleId, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        document.querySelector(`[data-module-id="${moduleId}"]`).remove();
                        showToast('Module supprimé', 'success');
                    }
                });
            }
        }

        // Prévisualiser la page
        function previewPage() {
            window.open('/page/<?php echo $page['slug']; ?>', '_blank');
        }

        // Drag & Drop pour réorganiser les modules
        const modulesContainer = document.getElementById('modules-container');
        let draggedElement = null;

        modulesContainer.addEventListener('dragstart', function(e) {
            if (e.target.classList.contains('module-item')) {
                draggedElement = e.target;
                e.target.classList.add('dragging');
            }
        });

        modulesContainer.addEventListener('dragend', function(e) {
            if (e.target.classList.contains('module-item')) {
                e.target.classList.remove('dragging');
            }
        });

        modulesContainer.addEventListener('dragover', function(e) {
            e.preventDefault();
            const afterElement = getDragAfterElement(modulesContainer, e.clientY);
            if (afterElement == null) {
                modulesContainer.appendChild(draggedElement);
            } else {
                modulesContainer.insertBefore(draggedElement, afterElement);
            }
        });

        modulesContainer.addEventListener('drop', function(e) {
            e.preventDefault();
            // Sauvegarder le nouvel ordre
            saveModulesOrder();
        });

        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('.module-item:not(.dragging)')];

            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;

                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }

        function saveModulesOrder() {
            const modules = [...document.querySelectorAll('.module-item')].map(el => el.dataset.moduleId);

            fetch('/api/modules/reorder', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ modules: modules })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showToast('Ordre sauvegardé', 'success');
                }
            });
        }
    </script>
</body>
</html>
