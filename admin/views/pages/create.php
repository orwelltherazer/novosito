<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle page - NovoSito Admin</title>
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
                <h1>Nouvelle page</h1>
                <a href="/admin/pages" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="/admin/pages">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Titre SEO</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title">
                            <small class="form-text text-muted">Si vide, le titre de la page sera utilisé</small>
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Description SEO</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="template" class="form-label">Template</label>
                                    <select class="form-select" id="template" name="template">
                                        <option value="default">Par défaut</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Statut</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="draft">Brouillon</option>
                                        <option value="published">Publié</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_homepage" name="is_homepage">
                                <label class="form-check-label" for="is_homepage">
                                    Définir comme page d'accueil
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Créer la page
                            </button>
                            <a href="/admin/pages" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/admin/assets/js/admin.js"></script>
</body>
</html>
