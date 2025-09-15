<?php 
include 'partials/header.php'; 

$news_file = 'data/news.json';
$all_news = file_exists($news_file) ? json_decode(file_get_contents($news_file), true) : [];
?>

<main>
    <section class="banner-pagina" style="background-image: linear-gradient(rgba(0, 51, 102, 0.6), rgba(0, 51, 102, 0.6)), url('Img/site 02.jpg');">
        <div class="container">
            <h1>Jornal Monteiro</h1>
            <p>Fique por dentro de todas as novidades</p>
        </div>
    </section>

    <section class="noticias-section" style="padding-top: 60px;">
        <div class="container">
            <div class="noticias-grid">
                <?php if (empty($all_news)): ?>
                    <p>Nenhuma notícia publicada no momento.</p>
                <?php else: foreach($all_news as $news_item): ?>
                    <a href="#" class="noticia-card">
                        <div class="noticia-imagem">
                            <img src="<?php echo htmlspecialchars($news_item['image']); ?>" alt="<?php echo htmlspecialchars($news_item['title']); ?>">
                        </div>
                        <div class="noticia-conteudo">
                            <span class="noticia-categoria"><?php echo htmlspecialchars($news_item['category']); ?></span>
                            <h3 class="noticia-titulo"><?php echo htmlspecialchars($news_item['title']); ?></h3>
                            <p class="noticia-resumo"><?php echo htmlspecialchars($news_item['summary']); ?></p>
                            <div class="noticia-meta">
                                <span class="noticia-data"><?php echo htmlspecialchars($news_item['date']); ?></span>
                                <span class="noticia-leia-mais">Leia Mais <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include 'partials/footer.php'; ?>