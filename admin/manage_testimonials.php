<?php
include 'auth.php';

$testimonials_file = '../data/testimonials.json';

// Função para extrair o ID de um vídeo do YouTube de qualquer URL
function getYouTubeId($url) {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
    return $match[1] ?? null;
}

function getTestimonials() {
    global $testimonials_file;
    if (!file_exists($testimonials_file)) return [];
    return json_decode(file_get_contents($testimonials_file), true) ?? [];
}

function saveTestimonials($testimonials) {
    global $testimonials_file;
    file_put_contents($testimonials_file, json_encode($testimonials, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Adicionar ou Editar Depoimento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['author'])) {
    $testimonials = getTestimonials();
    $youtube_id = getYouTubeId($_POST['youtube_url']);

    if (!$youtube_id) {
        die("URL do YouTube inválida. Por favor, volte e insira uma URL válida.");
    }

    $testimonial_data = [
        'id' => $_POST['id'] ? (int)$_POST['id'] : time(),
        'youtube_id' => $youtube_id,
        'date' => $_POST['date'], // <-- CAMPO DE DATA ADICIONADO
        'text' => $_POST['text'],
        'author' => $_POST['author']
    ];

    $found = false;
    if ($_POST['id']) {
        foreach ($testimonials as $key => $item) {
            if ($item['id'] == $_POST['id']) {
                $testimonials[$key] = $testimonial_data;
                $found = true;
                break;
            }
        }
    }
    if (!$found) {
        array_unshift($testimonials, $testimonial_data);
    }
    
    saveTestimonials($testimonials);
    header('Location: manage_testimonials.php');
    exit;
}

// Deletar Depoimento
if (isset($_GET['delete'])) {
    $testimonials = getTestimonials();
    $id_to_delete = (int)$_GET['delete'];
    $testimonials = array_filter($testimonials, fn($item) => $item['id'] != $id_to_delete);
    saveTestimonials(array_values($testimonials));
    header('Location: manage_testimonials.php');
    exit;
}

$all_testimonials = getTestimonials();
$edit_testimonial = null;
if (isset($_GET['edit'])) {
    $id_to_edit = (int)$_GET['edit'];
    foreach($all_testimonials as $item) {
        if ($item['id'] == $id_to_edit) {
            $edit_testimonial = $item;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Gerenciar Depoimentos</title><link rel="stylesheet" href="style.css">
</head>
<body>
<div class="admin-container">
    <header class="admin-header">
        <h1>Gerenciar Depoimentos</h1>
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
            <h2><?php echo $edit_testimonial ? 'Editar Depoimento' : 'Adicionar Novo Depoimento'; ?></h2>
            <form method="POST" action="manage_testimonials.php">
                <input type="hidden" name="id" value="<?php echo $edit_testimonial['id'] ?? ''; ?>">
                <div class="input-group">
                    <label for="youtube_url">URL do Vídeo no YouTube</label>
                    <input type="url" id="youtube_url" name="youtube_url" value="<?php echo $edit_testimonial ? 'https://www.youtube.com/watch?v=' . htmlspecialchars($edit_testimonial['youtube_id']) : ''; ?>" required placeholder="https://www.youtube.com/watch?v=...">
                </div>
                <div class="input-group">
                    <label for="date">Data de Publicação</label>
                    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($edit_testimonial['date'] ?? date('Y-m-d')); ?>" required>
                </div>
                <div class="input-group">
                    <label for="author">Autor do Depoimento (Ex: Nome, Cargo)</label>
                    <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($edit_testimonial['author'] ?? ''); ?>" required>
                </div>
                <div class="input-group">
                    <label for="text">Texto do Depoimento</label>
                    <textarea id="text" name="text" rows="5" required><?php echo htmlspecialchars($edit_testimonial['text'] ?? ''); ?></textarea>
                </div>
                <button type="submit"><?php echo $edit_testimonial ? 'Atualizar Depoimento' : 'Publicar Depoimento'; ?></button>
                <?php if ($edit_testimonial): ?><a href="manage_testimonials.php" class="cancel-btn">Cancelar Edição</a><?php endif; ?>
            </form>
        </div>
        <div class="content-list">
            <h2>Depoimentos Publicados</h2>
            <table>
                <thead><tr><th>Thumbnail</th><th>Autor</th><th>Data</th><th>Ações</th></tr></thead>
                <tbody>
                    <?php if (empty($all_testimonials)): ?>
                        <tr><td colspan="4">Nenhum depoimento publicado.</td></tr>
                    <?php else: foreach($all_testimonials as $item): ?>
                        <tr>
                            <td><img src="https://i.ytimg.com/vi/<?php echo htmlspecialchars($item['youtube_id']); ?>/default.jpg" alt="Thumbnail"></td>
                            <td><?php echo htmlspecialchars($item['author']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($item['date'])); ?></td>
                            <td class="actions">
                                <a href="manage_testimonials.php?edit=<?php echo $item['id']; ?>" class="edit-btn">Editar</a>
                                <a href="manage_testimonials.php?delete=<?php echo $item['id']; ?>" class="delete-btn" onclick="return confirm('Tem certeza?');">Excluir</a>
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