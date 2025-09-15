<?php
include 'auth.php';

$news_file = '../data/news.json';

function getNews() {
    global $news_file;
    if (!file_exists($news_file)) return [];
    return json_decode(file_get_contents($news_file), true);
}

function saveNews($news) {
    global $news_file;
    file_put_contents($news_file, json_encode($news, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $news = getNews();
    $image_path = $_POST['existing_image'] ?? '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $image_name = uniqid('news_') . '-' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = 'uploads/' . $image_name;
        }
    }

    $post_data = [
        'id' => $_POST['id'] ? (int)$_POST['id'] : time(),
        'title' => $_POST['title'],
        'summary' => $_POST['summary'],
        'category' => $_POST['category'],
        'date' => date('d \d\e F \d\e Y'),
        'image' => $image_path
    ];

    if ($_POST['id']) {
        foreach ($news as $key => $post) {
            if ($post['id'] == $_POST['id']) {
                $news[$key] = $post_data;
                break;
            }
        }
    } else {
        array_unshift($news, $post_data);
    }
    
    saveNews($news);
    header('Location: manage_news.php');
    exit;
}

if (isset($_GET['delete'])) {
    $news = getNews();
    $id_to_delete = (int)$_GET['delete'];
    $news = array_filter($news, fn($post) => $post['id'] != $id_to_delete);
    saveNews(array_values($news));
    header('Location: manage_news.php');
    exit;
}

$all_news = getNews();
$edit_post = null;
if (isset($_GET['edit'])) {
    $id_to_edit = (int)$_GET['edit'];
    foreach($all_news as $post) {
        if ($post['id'] == $id_to_edit) {
            $edit_post = $post;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Gerenciar Notícias</title><link rel="stylesheet" href="style.css">
</head>
<body>
<div class="admin-container">
    <header class="admin-header">
        <h1>Gerenciar Notícias</h1>
        <nav>
    <a href="index.php">Dashboard</a>
    <a href="manage_slides.php">Gerenciar Slides</a> <a href="manage_news.php">Gerenciar Notícias</a>
    <a href="manage_events.php">Gerenciar Eventos</a>
    <a href="manage_testimonials.php">Gerenciar Depoimentos</a>
    <a href="logout.php" class="logout-btn">Sair</a>
</nav>
    </header>
    <main>
        <div class="form-container">
            <h2><?php echo $edit_post ? 'Editar Notícia' : 'Adicionar Nova Notícia'; ?></h2>
            <form method="POST" action="manage_news.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $edit_post['id'] ?? ''; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $edit_post['image'] ?? ''; ?>">
                <div class="input-group"><label for="title">Título</label><input type="text" id="title" name="title" value="<?php echo htmlspecialchars($edit_post['title'] ?? ''); ?>" required></div>
                <div class="input-group"><label for="category">Categoria</label><input type="text" id="category" name="category" value="<?php echo htmlspecialchars($edit_post['category'] ?? 'Eventos'); ?>" required></div>
                <div class="input-group"><label for="summary">Resumo</label><textarea id="summary" name="summary" rows="4" required><?php echo htmlspecialchars($edit_post['summary'] ?? ''); ?></textarea></div>
                <div class="input-group"><label for="image">Imagem</label><input type="file" id="image" name="image" accept="image/*">
                    <?php if ($edit_post && !empty($edit_post['image'])): ?><p>Imagem atual: <img src="../<?php echo htmlspecialchars($edit_post['image']); ?>" alt="Imagem atual" width="100"></p><?php endif; ?>
                </div>
                <button type="submit"><?php echo $edit_post ? 'Atualizar Notícia' : 'Publicar Notícia'; ?></button>
                <?php if ($edit_post): ?><a href="manage_news.php" class="cancel-btn">Cancelar Edição</a><?php endif; ?>
            </form>
        </div>
        <div class="content-list">
            <h2>Notícias Publicadas</h2>
            <table>
                <thead><tr><th>Imagem</th><th>Título</th><th>Data</th><th>Ações</th></tr></thead>
                <tbody>
                    <?php if (empty($all_news)): ?>
                        <tr><td colspan="4">Nenhuma notícia publicada ainda.</td></tr>
                    <?php else: foreach($all_news as $post): ?>
                        <tr>
                            <td><img src="../<?php echo htmlspecialchars($post['image'] ?? 'img/placeholder.png'); ?>" alt="" width="80"></td>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo htmlspecialchars($post['date']); ?></td>
                            <td class="actions">
                                <a href="manage_news.php?edit=<?php echo $post['id']; ?>" class="edit-btn">Editar</a>
                                <a href="manage_news.php?delete=<?php echo $post['id']; ?>" class="delete-btn" onclick="return confirm('Tem certeza?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>