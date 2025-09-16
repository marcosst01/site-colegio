<?php 
include 'partials/header.php'; 

$news_file = 'data/news.json';
$all_news = file_exists($news_file) ? json_decode(file_get_contents($news_file), true) : [];
?>

<main>
    <section class="banner-pagina" style="background-image: linear-gradient(rgba(0, 51, 102, 0.6), rgba(0, 51, 102, 0.6)), url('Img/site 02.jpg');">
        <div class="container"><h1>Jornal Monteiro</h1><p>Fique por dentro de todas as novidades</p></div>
    </section>

    <section class="noticias-section" style="padding-top: 60px;">
        <div class="container">
            <div class="noticias-grid">
                <?php 
                // Define o idioma para Português para formatar a data
                setlocale(LC_TIME, 'pt_BR.utf-8', 'pt_BR', 'portuguese');

                if (empty($all_news)): ?>
                    <p>Nenhuma notícia publicada no momento.</p>
                <?php else: foreach($all_news as $news_item): ?>
                    <?php
                        // --- LÓGICA DO RESUMO AUTOMÁTICO CORRIGIDA ---
                        $full_content_decoded = html_entity_decode($news_item['full_content'] ?? '');
                        $text_only_content = trim(strip_tags($full_content_decoded));
                        $excerpt = mb_substr($text_only_content, 0, 150);
                        if (mb_strlen($text_only_content) > 150) {
                            $excerpt .= '...';
                        }
                        // ---------------------------------------------
                    ?>
                    <a href="noticia-detalhe.php?id=<?php echo htmlspecialchars($news_item['id']); ?>" class="noticia-card">
                        <div class="noticia-imagem"><img src="<?php echo htmlspecialchars($news_item['image']); ?>" alt="<?php echo htmlspecialchars($news_item['title']); ?>"></div>
                        <div class="noticia-conteudo">
                            <span class="noticia-categoria"><?php echo htmlspecialchars($news_item['category']); ?></span>
                            <h3 class="noticia-titulo"><?php echo htmlspecialchars($news_item['title']); ?></h3>
                            <p class="noticia-resumo"><?php echo htmlspecialchars($excerpt); ?></p>
                            <div class="noticia-meta">
                                <span class="noticia-data"><?php echo strftime('%d de %B de %Y', strtotime($news_item['date'])); ?></span>
                                <span class="noticia-leia-mais">Leia Mais <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </section>
</main>

<section class="matriculas-banner"></section>
<?php include 'partials/footer.php'; ?>