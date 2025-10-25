<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pages - NovoSito Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/admin/assets/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-content">
        <?php include __DIR__ . '/../partials/topbar.php'; ?>

        <div class="content-wrapper p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Pages</h1>
                <a href="/admin/pages/create" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouvelle page
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <?php if (empty($pages)): ?>
                        <p class="text-muted">Aucune page n'a été créée pour le moment.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Slug</th>
                                        <th>Statut</th>
                                        <th>Accueil</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pages as $page): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($page['title']); ?></strong>
                                            </td>
                                            <td>
                                                <code><?php echo htmlspecialchars($page['slug']); ?></code>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = match($page['status']) {
                                                    'published' => 'success',
                                                    'draft' => 'warning',
                                                    'scheduled' => 'info',
                                                    default => 'secondary'
                                                };
                                                $statusLabel = match($page['status']) {
                                                    'published' => 'Publié',
                                                    'draft' => 'Brouillon',
                                                    'scheduled' => 'Programmé',
                                                    default => $page['status']
                                                };
                                                ?>
                                                <span class="badge bg-<?php echo $statusClass; ?>">
                                                    <?php echo $statusLabel; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($page['is_homepage']): ?>
                                                    <i class="fas fa-home text-primary"></i>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo date('d/m/Y', strtotime($page['created_at'])); ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/admin/pages/<?php echo $page['id']; ?>/edit" class="btn btn-outline-primary" title="Éditer">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="/page/<?php echo $page['slug']; ?>" class="btn btn-outline-secondary" target="_blank" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-outline-danger" onclick="deletePage(<?php echo $page['id']; ?>)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/admin/assets/js/admin.js"></script>
    <script>
    function deletePage(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette page ?')) {
            fetch('/admin/pages/' + id, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erreur lors de la suppression');
                }
            });
        }
    }
    </script>
</body>
</html>
