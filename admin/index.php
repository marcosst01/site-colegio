<?php include 'auth.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Painel Administrativo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1>Painel do Colégio Monteiro Lobato</h1>
            <nav>
                <a href="manage_slides.php">Gerenciar Slides</a>
                <a href="manage_news.php">Gerenciar Notícias</a>
                <a href="manage_events.php">Gerenciar Eventos</a>
                <a href="manage_testimonials.php">Gerenciar Depoimentos</a>
                <a href="manage_tv.php">Gerenciar TV Monteiro</a>
                <a href="manage_tv.php">Gerenciar TV Monteiro</a>
                <a href="logout.php" class="logout-btn">Sair</a>
        </header>
        <main class="dashboard">
            <h2>Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Selecione uma das opções no menu acima para começar a gerenciar o conteúdo do site.</p>
        </main>
    </div>
</body>
</html>