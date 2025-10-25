<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - NovoSito Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/admin/assets/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <div class="main-content">
        <?php include __DIR__ . '/partials/topbar.php'; ?>

        <div class="content-wrapper p-4">
            <h1 class="mb-4">Tableau de bord</h1>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Pages</h6>
                                    <h2 class="mb-0"><?php echo $stats['total_pages']; ?></h2>
                                </div>
                                <div class="stats-icon bg-primary">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Pages Publiées</h6>
                                    <h2 class="mb-0"><?php echo $stats['published_pages']; ?></h2>
                                </div>
                                <div class="stats-icon bg-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Brouillons</h6>
                                    <h2 class="mb-0"><?php echo $stats['draft_pages']; ?></h2>
                                </div>
                                <div class="stats-icon bg-warning">
                                    <i class="fas fa-edit"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Actions rapides</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <a href="/admin/pages/create" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Nouvelle page
                                </a>
                                <a href="/admin/pages" class="btn btn-secondary">
                                    <i class="fas fa-list me-2"></i>Voir toutes les pages
                                </a>
                                <a href="/admin/media" class="btn btn-secondary">
                                    <i class="fas fa-images me-2"></i>Médiathèque
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/admin/assets/js/admin.js"></script>
</body>
</html>
