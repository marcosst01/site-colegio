<?php
include 'auth.php';

$news_file = '../data/news.json';

// ... (funções PHP no topo do arquivo - getNews, saveNews, etc. permanecem as mesmas) ...
function getNews() { global $news_file; if (!file_exists($news_file)) return []; return json_decode(file_get_contents($news_file), true) ?? []; }
function saveNews($news) { global $news_file; file_put_contents($news_file, json_encode($news, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); }
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
        'full_content' => $_POST['full_content'],
        'category' => $_POST['category'],
        'date' => $_POST['date'],
        'image' => $image_path
    ];
    $found = false;
    if ($_POST['id']) {
        foreach ($news as $key => $post) {
            if ($post['id'] == $_POST['id']) {
                $news[$key] = $post_data;
                $found = true;
                break;
            }
        }
    }
    if (!$found) {
        array_unshift($news, $post_data);
    }
    saveNews($news);
    header('Location: manage_news.php');
    exit;
}
if (isset($_GET['delete'])) { $news = getNews(); $id_to_delete = (int)$_GET['delete']; $news = array_filter($news, fn($post) => $post['id'] != $id_to_delete); saveNews(array_values($news)); header('Location: manage_news.php'); exit; }
$all_news = getNews();
$edit_post = null;
if (isset($_GET['edit'])) { $id_to_edit = (int)$_GET['edit']; foreach($all_news as $post) { if ($post['id'] == $id_to_edit) { $edit_post = $post; break; } } }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Gerenciar Notícias</title><link rel="stylesheet" href="style.css">
    <script src="https://cdn.tiny.cloud/1/n8mm9wkzy6mups24t26bym0k2hmgb5nfmyzs05ovepc2jgmh/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({ selector: '#full_content', language: 'pt_BR' });
    </script>
</head>
<body>
<div class="admin-container">
    <header class="admin-header">
        <h1>Gerenciar Notícias</h1>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="manage_slides.php">Gerenciar Slides</a>
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
                <div class="input-group"><label for="date">Data da Publicação</label><input type="date" id="date" name="date" value="<?php echo htmlspecialchars($edit_post['date'] ?? date('Y-m-d')); ?>" required></div>
                
                <div class="input-group">
                    <label for="category">Categoria</label>
                    <select id="category" name="category" required>
                        <option value="Geral" <?php echo ($edit_post['category'] ?? 'Geral') == 'Geral' ? 'selected' : ''; ?>>Geral</option>
                        <option value="Educação Infantil" <?php echo ($edit_post['category'] ?? '') == 'Educação Infantil' ? 'selected' : ''; ?>>Educação Infantil</option>
                        <option value="Fundamental 1" <?php echo ($edit_post['category'] ?? '') == 'Fundamental 1' ? 'selected' : ''; ?>>Fundamental 1</option>
                        <option value="Fundamental 2" <?php echo ($edit_post['category'] ?? '') == 'Fundamental 2' ? 'selected' : ''; ?>>Fundamental 2</option>
                    </select>
                </div>
                
                <div class="input-group">
                    <label for="summary">Subtítulo (texto curto que aparece no banner)</label>
                    <textarea id="summary" name="summary" rows="3" required><?php echo htmlspecialchars($edit_post['summary'] ?? ''); ?></textarea>
                </div>
                
                <div class="input-group"><label for="full_content">Conteúdo Completo da Notícia</label><textarea id="full_content" name="full_content" rows="15"><?php echo htmlspecialchars($edit_post['full_content'] ?? ''); ?></textarea></div>
                <div class="input-group"><label for="image">Imagem de Destaque</label><input type="file" id="image" name="image" accept="image/*">
                    <?php if ($edit_post && !empty($edit_post['image'])): ?><p>Imagem atual: <img src="../<?php echo htmlspecialchars($edit_post['image']); ?>" width="100"></p><?php endif; ?>
                </div>
                <button type="submit"><?php echo $edit_post ? 'Atualizar Notícia' : 'Publicar Notícia'; ?></button>
                <?php if ($edit_post): ?><a href="manage_news.php" class="cancel-btn">Cancelar Edição</a><?php endif; ?>
            </form>
        </div>
        <div class="content-list">
            <h2>Notícias Publicadas</h2>
            <table>
                <thead><tr><th>Imagem</th><th>Título</th><th>Categoria</th><th>Data</th><th>Ações</th></tr></thead>
                <tbody>
                    <?php if (empty($all_news)): ?>
                        <tr><td colspan="5">Nenhuma notícia publicada ainda.</td></tr>
                    <?php else: foreach($all_news as $post): ?>
                        <tr>
                            <td><img src="../<?php echo htmlspecialchars($post['image'] ?? ''); ?>" width="80"></td>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo htmlspecialchars($post['category'] ?? 'Geral'); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($post['date'])); ?></td>
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