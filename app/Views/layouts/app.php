<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'CRM TPT'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/leads.css">
</head>
<body>
    <div class="app-shell">
        <?php require app_path('Views/components/sidebar.php'); ?>

        <div class="app-main">
            <?php require app_path('Views/components/topbar.php'); ?>

            <main class="app-content">
                <?php require app_path('Views/components/alerts.php'); ?>
                <?= $content ?>
            </main>
        </div>
    </div>
</body>
</html>
