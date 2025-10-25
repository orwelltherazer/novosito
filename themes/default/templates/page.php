<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page['meta_title'] ?? $page['title']); ?> - NovoSito</title>
    <meta name="description" content="<?php echo htmlspecialchars($page['meta_description'] ?? ''); ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Thème CSS -->
    <link rel="stylesheet" href="/themes/default/assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="site-header bg-primary text-white py-3">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <a href="/" class="text-white text-decoration-none">NovoSito</a>
                </h1>
                <nav>
                    <ul class="nav">
                        <li class="nav-item">
                            <a href="/" class="nav-link text-white">Accueil</a>
                        </li>
                        <!-- TODO: Ajouter la gestion des menus -->
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="site-content py-4">
        <div class="container">
            <?php if (!$page['is_homepage']): ?>
                <h1 class="page-title mb-4"><?php echo htmlspecialchars($page['title']); ?></h1>
            <?php endif; ?>

            <div class="page-modules">
                <?php if (!empty($modules)): ?>
                    <?php foreach ($modules as $moduleData): ?>
                        <?php
                        // Charge le module
                        $moduleClass = 'Modules\\' . ucfirst($moduleData['type']) . '\\' . ucfirst($moduleData['type']) . 'Module';
                        if (class_exists($moduleClass)) {
                            $module = new $moduleClass([
                                'id' => $moduleData['id'],
                                'content' => json_decode($moduleData['content'], true),
                                'settings' => json_decode($moduleData['settings'], true)
                            ]);
                            echo $module->render();
                        }
                        ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Cette page ne contient aucun contenu pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; <?php echo date('Y'); ?> NovoSito CMS. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>Propulsé par <strong>NovoSito CMS</strong></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Thème JS -->
    <script src="/themes/default/assets/js/script.js"></script>
</body>
</html>
