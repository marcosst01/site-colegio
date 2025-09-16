<?php include 'partials/header.php'; ?>

  <?php
    $slides_file = 'data/slides.json';
    $slides = file_exists($slides_file) ? json_decode(file_get_contents($slides_file), true) : [];
?>

<section class="slider">
    <?php if (!empty($slides)): ?>
    <div class="slider-content">
        
        <?php foreach ($slides as $index => $slide): ?>
            <input type="radio" name="btn-radio" id="radio<?php echo $index + 1; ?>" <?php echo $index === 0 ? 'checked' : ''; ?>>
        <?php endforeach; ?>

        <div class="slides-wrapper">
            <?php foreach ($slides as $index => $slide): ?>
                <div class="slide-box <?php echo $index === 0 ? 'primeiro' : ''; ?>">
                    <?php if (!empty($slide['link_url'])): ?>
                        <a href="<?php echo htmlspecialchars($slide['link_url']); ?>">
                            <img class="img-desktop" src="<?php echo htmlspecialchars($slide['desktop_image']); ?>" alt="<?php echo htmlspecialchars($slide['alt_text']); ?>">
                            <img class="img-mobile" src="<?php echo htmlspecialchars($slide['mobile_image']); ?>" alt="<?php echo htmlspecialchars($slide['alt_text']); ?>">
                        </a>
                    <?php else: ?>
                        <img class="img-desktop" src="<?php echo htmlspecialchars($slide['desktop_image']); ?>" alt="<?php echo htmlspecialchars($slide['alt_text']); ?>">
                        <img class="img-mobile" src="<?php echo htmlspecialchars($slide['mobile_image']); ?>" alt="<?php echo htmlspecialchars($slide['alt_text']); ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="nav-auto">
            <?php foreach ($slides as $index => $slide): ?>
                <div class="auto-btn<?php echo $index + 1; ?>"></div>
            <?php endforeach; ?>
        </div>
        
        <button class="arrow left-arrow" onclick="voltarSlide()">&#10094;</button>
        <button class="arrow right-arrow" onclick="avancarSlide()">&#10095;</button>
        
        <div class="nav-manual">
            <?php foreach ($slides as $index => $slide): ?>
                <label for="radio<?php echo $index + 1; ?>" class="manual-btn"></label>
            <?php endforeach; ?>
        </div>

    </div>
    <?php endif; ?>
</section>

<script>
    const TOTAL_SLIDES = <?php echo count($slides); ?>;
</script>

<section class="matriculas-banner">
    <div class="banner-content">
        <p class="banner-text"><strong>Matrículas abertas!</strong> Venha conhecer o Colégio Monteiro Lobato.</p>
        <a href="#" class="banner-button">Agende uma visita</a>
    </div>
</section>

<section class="card-container">
    <a href="educacao-infantil.php" class="info-card card-blue"><img src="Img/ed.infantil.jpg" alt="Educação Infantil" class="card-img"><div class="card-overlay"><p class="card-text">Uma base sólida para os primeiros passos na jornada do conhecimento, com afeto e ludicidade.</p><span class="card-link">Saiba mais</span></div><div class="card-title-bar"><h2>EDUCAÇÃO INFANTIL</h2></div></a>
    <a href="#" class="info-card card-orange"><img src="Img/fundamental.1.jpg" alt="Ensino Fundamental I" class="card-img"><div class="card-overlay"><p class="card-text">Desenvolvimento da autonomia, do raciocínio lógico e do prazer em aprender (1º ao 5º ano).</p><span class="card-link">Saiba mais</span></div><div class="card-title-bar"><h2>ENSINO FUNDAMENTAL I</h2></div></a>
    <a href="#" class="info-card card-dark-blue"><img src="Img/fundamental.2.jpg" alt="Ensino Fundamental II" class="card-img"><div class="card-overlay"><p class="card-text">Ampliação do conhecimento e preparação para os desafios futuros (6º ao 9º ano).</p><span class="card-link">Saiba mais</span></div><div class="card-title-bar"><h2>ENSINO FUNDAMENTAL II</h2></div></a>
</section>

<section class="seja-mais">
    <div class="container seja-mais-flex">
        <div class="seja-mais-img"><img src="Img/crianças.webp" alt="Imagem Seja Mais"></div>
        <div class="seja-mais-texto">
            <h2 class="titulo-seja-mais">Seja mais Monteiro</h2>
            <p class="descricao-seja-mais">Investimos fortemente em uma proposta pedagógica robusta e inovadora como parte do nosso compromisso. Da Educação Infantil ao Ensino Fundamental II oferecemos um ensino de excelência, promovendo a autonomia e o desenvolvimento integral das crianças e dos jovens estudantes, em um ambiente criativo, dinâmico e interativo, despertando assim, a paixão pelo ensino.</p>
            <a href="#" class="btn-saiba-mais">Saiba mais</a>
        </div>
    </div>
</section>

<section class="events-section">
    <h2 class="section-title">Conheça os nossos <strong>Eventos</strong></h2>
    <div class="events-grid">
        <?php
        $events_file = 'data/events.json';
        $events = file_exists($events_file) ? json_decode(file_get_contents($events_file), true) : [];
        
        // <<< LÓGICA PARA ORDENAR POR DATA >>>
        if (!empty($events)) {
            usort($events, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }
        
        $latest_events = array_slice($events, 0, 8);
        
        if (empty($latest_events)) {
            echo "<p style='color: white; grid-column: 1 / -1;'>Nenhum evento publicado recentemente.</p>";
        } else {
            // Na seção "Conheça os nossos Eventos"
foreach ($latest_events as $event) {
    // LÓGICA ATUALIZADA DA FOTO DE CAPA
    $cover_image = !empty($event['cover_image']) ? htmlspecialchars($event['cover_image']) : (!empty($event['images']) ? htmlspecialchars($event['images'][0]) : 'img/placeholder.png');
    
    echo '<a href="evento-detalhe.php?id=' . $event['id'] . '" class="event-card">'; 
    echo '    <img src="' . $cover_image . '" alt="' . htmlspecialchars($event['title']) . '">';
    echo '    <div class="event-caption">';
    echo '        <p>' . htmlspecialchars($event['title']) . '</p>';
    echo '    </div>';
    echo '</a>';
}
        }
        ?>
    </div>
    <div class="ver-mais-wrapper">
        <a href="eventos.php" class="btn-ver-mais">Ver todos os eventos</a>
    </div>
</section>


<section class="depoimentos">
    <h2 class="titulo-secao">Depoimentos</h2>

    <?php
        $testimonials_file = 'data/testimonials.json';
        $testimonials = file_exists($testimonials_file) ? json_decode(file_get_contents($testimonials_file), true) : [];

        // LÓGICA PARA ORDENAR POR DATA (DO MAIS RECENTE PARA O MAIS ANTIGO)
        if (!empty($testimonials)) {
            usort($testimonials, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }
    ?>

    <?php if (!empty($testimonials)): ?>
    <div class="swiper meu-slider-depoimentos">
        <div class="swiper-wrapper">
            
            <?php foreach($testimonials as $item): ?>
            <div class="swiper-slide">
                <div class="video-container-popup" data-youtube-id="<?php echo htmlspecialchars($item['youtube_id']); ?>">
                    <img src="https://i.ytimg.com/vi/<?php echo htmlspecialchars($item['youtube_id']); ?>/hqdefault.jpg" alt="Depoimento de <?php echo htmlspecialchars($item['author']); ?>">
                    <div class="play-icon"></div>
                </div>
                <div class="depoimento-texto">
                    <h3 class="video-titulo"><?php echo htmlspecialchars($item['text']); ?></h3>
                    <p class="video-autor"><?php echo htmlspecialchars($item['author']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
        
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
    <?php else: ?>
        <p style="text-align: center;">Nenhum depoimento cadastrado no momento.</p>
    <?php endif; ?>

    </section>

<div id="video-modal" class="modal"><div class="modal-content"><span class="modal-close">×</span><div id="modal-video-container"></div></div></div>

<section class="tv-monteiro-section">
    <div class="container">
        <h2 class="section-title">TV <strong>Monteiro</strong></h2>
        <div class="tv-grid">
            <div class="tv-card"><div class="video-container-popup" data-youtube-id="COk2h_p_iI4"><img src="https://i.ytimg.com/vi/COk2h_p_iI4/hqdefault.jpg" alt="Vídeo 1"><div class="play-icon"></div></div><div class="tv-card-info"><h4 class="tv-card-title">Estudo do Meio no Sítio do Picapau</h4><p class="tv-card-date">Publicado em: 02 de Julho de 2025</p></div></div>
            <div class="tv-card"><div class="video-container-popup" data-youtube-id="zJ7LADOWJH0"><img src="https://i.ytimg.com/vi/zJ7LADOWJH0/hqdefault.jpg" alt="Vídeo 2"><div class="play-icon"></div></div><div class="tv-card-info"><h4 class="tv-card-title">Apresentação da Feira de Ciências</h4><p class="tv-card-date">Publicado em: 25 de Junho de 2025</p></div></div>
            <div class="tv-card"><div class="video-container-popup" data-youtube-id="o0UbwsgyKvE"><img src="https://i.ytimg.com/vi/o0UbwsgyKvE/hqdefault.jpg" alt="Vídeo 3"><div class="play-icon"></div></div><div class="tv-card-info"><h4 class="tv-card-title">Homenagem ao Dia das Mães</h4><p class="tv-card-date">Publicado em: 12 de Maio de 2025</p></div></div>
            <div class="tv-card"><div class="video-container-popup" data-youtube-id="QoDGaT_8G80"><img src="https://i.ytimg.com/vi/QoDGaT_8G80/hqdefault.jpg" alt="Vídeo 4"><div class="play-icon"></div></div><div class="tv-card-info"><h4 class="tv-card-title">Mensagem da Direção</h4><p class="tv-card-date">Publicado em: 01 de Maio de 2025</p></div></div>
        </div>
    </div>
</section>

<section class="noticias-section">
    <div class="container">
        <h2 class="section-title">Últimas <strong>Notícias</strong></h2>
        <p class="section-subtitle">Acompanhe os principais acontecimentos e novidades do Colégio Monteiro Lobato.</p>
        <div class="noticias-grid">
            <?php
            $news_file = 'data/news.json';
            $all_news = file_exists($news_file) ? json_decode(file_get_contents($news_file), true) : [];
            $latest_news = array_slice($all_news, 0, 3);
            
            // Define o idioma para Português para formatar a data
            setlocale(LC_TIME, 'pt_BR.utf-8', 'pt_BR', 'portuguese');

            if (empty($latest_news)) {
                echo "<p style='grid-column: 1 / -1;'>Nenhuma notícia publicada recentemente.</p>";
            } else {
                foreach ($latest_news as $news_item) {
                    // --- LÓGICA DO RESUMO AUTOMÁTICO CORRIGIDA ---
                    $full_content_decoded = html_entity_decode($news_item['full_content'] ?? '');
                    $text_only_content = trim(strip_tags($full_content_decoded));
                    $excerpt = mb_substr($text_only_content, 0, 150);
                    if (mb_strlen($text_only_content) > 150) {
                        $excerpt .= '...';
                    }
                    // ---------------------------------------------

                    echo '<a href="noticia-detalhe.php?id=' . $news_item['id'] . '" class="noticia-card">';
                    echo '    <div class="noticia-imagem"><img src="' . htmlspecialchars($news_item['image']) . '" alt="' . htmlspecialchars($news_item['title']) . '"></div>';
                    echo '    <div class="noticia-conteudo">';
                    echo '        <span class="noticia-categoria">' . htmlspecialchars($news_item['category']) . '</span>';
                    echo '        <h3 class="noticia-titulo">' . htmlspecialchars($news_item['title']) . '</h3>';
                    echo '        <p class="noticia-resumo">' . htmlspecialchars($excerpt) . '</p>';
                    echo '        <div class="noticia-meta">';
                    // --- LÓGICA DA DATA CORRIGIDA ---
                    echo '            <span class="noticia-data">' . strftime('%d de %B de %Y', strtotime($news_item['date'])) . '</span>';
                    echo '            <span class="noticia-leia-mais">Leia Mais <i class="fas fa-arrow-right"></i></span>';
                    echo '        </div></div></a>';
                }
            }
            ?>
        </div>
         <div class="ver-mais-wrapper">
            <a href="noticias.php" class="btn-ver-mais-noticias">Ver todas as notícias</a>
            </div>
    </div>
</section>

<section class="instagram-section">
    <div class="instagram-header">
        <i class="fab fa-instagram instagram-logo-icon"></i>
        <h2 class="section-title">Acompanhe nosso Instagram</h2>
        <a href="https://instagram.com/colegiomonteiro" target="_blank" rel="noopener noreferrer" class="instagram-handle">@monteirolobatonline</a>
    </div>
    <script src="https://static.elfsight.com/platform/platform.js" async></script>
    <div class="elfsight-app-f26553f1-081f-40e4-a067-65343a8cb176" data-elfsight-app-lazy></div>
</section>

<section class="matriculas-banner">
    <div class="banner-content">
        <p class="banner-text">
            <strong>Matrículas abertas!</strong> Venha conhecer o Colégio Monteiro Lobato.
        </p>
        <a href="#" class="banner-button">
            Agende uma visita
        </a>
    </div>
</section>

<?php include 'partials/footer.php'; ?>