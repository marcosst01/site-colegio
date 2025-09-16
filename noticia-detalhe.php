<?php
include 'partials/header.php';

$news_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$noticia = null;
$noticias_recentes = [];
$all_news = [];

$news_file = 'data/news.json';
if (file_exists($news_file)) {
    $all_news = json_decode(file_get_contents($news_file), true) ?? [];
}

if ($news_id > 0 && !empty($all_news)) {
    foreach ($all_news as $item) {
        if ($item['id'] === $news_id) {
            $noticia = $item;
            break;
        }
    }
    foreach ($all_news as $item) {
        if (count($noticias_recentes) < 4 && $item['id'] !== $news_id) {
            $noticias_recentes[] = $item;
        }
    }
}
?>

<main>
    <?php if ($noticia): ?>
        <?php 
            // Define o idioma para Português
            setlocale(LC_TIME, 'pt_BR.utf-8', 'pt_BR', 'portuguese');
            $data_formatada = strftime('%d de %B de %Y', strtotime($noticia['date']));
            $foto_destaque = !empty($noticia['image']) ? $noticia['image'] : 'img/placeholder.png';

            // Lógica do Tempo de Leitura
            $word_count = str_word_count(strip_tags($noticia['full_content']));
            $reading_time = ceil($word_count / 200);
            $reading_time = ($reading_time < 1) ? 1 : $reading_time; // Mínimo de 1 min
            $reading_time_text = $reading_time . " min de leitura";
        ?>
        
        <section class="news-banner" style="background-image: url('<?php echo htmlspecialchars($foto_destaque); ?>');">
            <div class="container">
                <div class="news-meta">
                    <span><?php echo htmlspecialchars($noticia['category']); ?></span>
                    <span><?php echo $data_formatada; ?></span>
                    <span class="reading-time"><?php echo $reading_time_text; ?></span>
                </div>
                <h1><?php echo htmlspecialchars($noticia['title']); ?></h1>
                <p class="subtitle"><?php echo htmlspecialchars($noticia['summary'] ?? ''); ?></p>
            </div>
        </section>

        <div class="article-detail-container">
            <div class="article-main-content article-content">
                <?php echo $noticia['full_content']; ?>
            </div>
            <aside class="article-sidebar">
                <div class="sidebar-widget">
                    <h3>Últimas Notícias</h3>
                    <ul>
                        <?php if(!empty($noticias_recentes)): ?>
                            <?php foreach($noticias_recentes as $item_recente): ?>
                                <li><a href="noticia-detalhe.php?id=<?php echo $item_recente['id']; ?>"><?php echo htmlspecialchars($item_recente['title']); ?></a></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>Nenhuma outra notícia para mostrar.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </aside>
        </div>

    <?php else: ?>
        <section class="secao-introducao"><div class="container" style="text-align: center;"><h2>Notícia não encontrada</h2></div></section>
    <?php endif; ?>
</main>

<?php include 'partials/footer.php'; ?>