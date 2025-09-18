<?php
include 'auth.php';

$tv_file = '../data/tv_monteiro.json';

function getYoutubeData($url) {
    // URL do oEmbed do YouTube para buscar dados sem chave de API
    $oembed_url = 'https://www.youtube.com/oembed?url=' . urlencode($url) . '&format=json';
    
    // O @ suprime erros caso o vídeo não seja encontrado
    $json_data = @file_get_contents($oembed_url);
    if ($json_data === FALSE) {
        return null; // Retorna nulo se o vídeo não for encontrado ou a URL for inválida
    }
    return json_decode($json_data, true);
}

function getYouTubeId($url) {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
    return $match[1] ?? null;
}

function getVideos() {
    global $tv_file;
    if (!file_exists($tv_file)) return [];
    return json_decode(file_get_contents($tv_file), true) ?? [];
}

function saveVideos($videos) {
    global $tv_file;
    file_put_contents($tv_file, json_encode($videos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Adicionar novo vídeo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['youtube_url'])) {
    $videos = getVideos();
    $url = $_POST['youtube_url'];
    
    $video_id = getYouTubeId($url);
    $youtube_data = getYoutubeData($url);

    if ($video_id && $youtube_data) {
        $new_video = [
            'id' => time(),
            'video_id' => $video_id,
            'title' => $youtube_data['title'],
            'thumbnail_url' => $youtube_data['thumbnail_url']
        ];
        array_unshift($videos, $new_video);
        saveVideos($videos);
    } else {
        // Opcional: criar uma mensagem de erro
        $_SESSION['error_message'] = "Não foi possível encontrar o vídeo do YouTube. Verifique se o link está correto.";
    }
    
    header('Location: manage_tv.php');
    exit;
}

// Deletar vídeo
if (isset($_GET['delete'])) {
    $videos = getVideos();
    $id_to_delete = (int)$_GET['delete'];
    $videos = array_filter($videos, fn($video) => $video['id'] != $id_to_delete);
    saveVideos(array_values($videos));
    header('Location: manage_tv.php');
    exit;
}

$all_videos = getVideos();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Gerenciar TV Monteiro</title><link rel="stylesheet" href="style.css">
</head>
<body>
<div class="admin-container">
    <header class="admin-header">
        <h1>Gerenciar TV Monteiro</h1>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="manage_slides.php">Gerenciar Slides</a>
            <a href="manage_news.php">Gerenciar Notícias</a>
            <a href="manage_events.php">Gerenciar Eventos</a>
            <a href="manage_testimonials.php">Gerenciar Depoimentos</a>
            <a href="logout.php" class="logout-btn">Sair</a>
        </nav>
    </header>
    <main>
        <div class="form-container">
            <h2>Adicionar Novo Vídeo</h2>
            <form method="POST" action="manage_tv.php">
                <div class="input-group">
                    <label for="youtube_url">Cole o Link do Vídeo do YouTube aqui</label>
                    <input type="url" id="youtube_url" name="youtube_url" required placeholder="https://www.youtube.com/watch?v=...">
                </div>
                <button type="submit">Adicionar Vídeo</button>
            </form>
            <?php 
                if (isset($_SESSION['error_message'])) {
                    echo '<p class="error">' . $_SESSION['error_message'] . '</p>';
                    unset($_SESSION['error_message']);
                }
            ?>
        </div>
        <div class="content-list">
            <h2>Vídeos na Página Inicial</h2>
            <table>
                <thead><tr><th>Thumbnail</th><th>Título</th><th>Ações</th></tr></thead>
                <tbody>
                    <?php if (empty($all_videos)): ?>
                        <tr><td colspan="3">Nenhum vídeo adicionado.</td></tr>
                    <?php else: foreach($all_videos as $video): ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($video['thumbnail_url']); ?>" width="120"></td>
                            <td><?php echo htmlspecialchars($video['title']); ?></td>
                            <td class="actions">
                                <a href="manage_tv.php?delete=<?php echo $video['id']; ?>" class="delete-btn" onclick="return confirm('Tem certeza?');">Excluir</a>
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