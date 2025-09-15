<?php
include 'partials/header.php';

// --- LÓGICA PRINCIPAL DA PÁGINA ---
$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$evento = null;
$proximos_eventos = [];
$all_events = [];

$events_file = 'data/events.json';
if (file_exists($events_file)) {
    $all_events = json_decode(file_get_contents($events_file), true) ?? [];
}

if ($event_id > 0 && !empty($all_events)) {
    // Encontra o evento atual
    foreach ($all_events as $item) {
        if ($item['id'] === $event_id) {
            $evento = $item;
            break;
        }
    }

    // Ordena todos os eventos por data para a sidebar
    usort($all_events, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));

    // Pega os próximos 4 eventos (excluindo o atual)
    foreach ($all_events as $item) {
        if (count($proximos_eventos) < 4 && $item['id'] !== $event_id) {
            $proximos_eventos[] = $item;
        }
    }
}
?>

<main>
    <?php if ($evento): ?>
        <?php 
            $foto_capa = !empty($evento['cover_image']) ? $evento['cover_image'] : (!empty($evento['images']) ? $evento['images'][0] : 'img/placeholder.png');
            setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt', 'portuguese');
            $data_formatada = strftime('%d de %B de %Y', strtotime($evento['date']));
        ?>
        
        <div class="event-hero" style="background-image: url('<?php echo htmlspecialchars($foto_capa); ?>');">
            <div class="event-hero-content">
                <div class="event-meta">
                    <span class="category"><?php echo htmlspecialchars($evento['category']); ?></span>
                    <span class="separator">|</span>
                    <span class="date"><?php echo $data_formatada; ?></span>
                </div>
                <h1><?php echo htmlspecialchars($evento['title']); ?></h1>
                <p><?php echo htmlspecialchars($evento['description']); ?></p>
            </div>
        </div>

        <div class="event-detail-container">
            <div class="event-main-content">
                
                <?php
                    $images = $evento['images'] ?? [];
                    // A foto de capa agora também fará parte da galeria
                    $images_per_page = 12;
                    $total_images = count($images);
                    $total_pages = ceil($total_images / $images_per_page);
                    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($current_page - 1) * $images_per_page;
                    $paginated_images = array_slice($images, $offset, $images_per_page);
                ?>

                <div class="photo-gallery-grid">
                    <?php if (!empty($paginated_images)): ?>
                        <?php foreach ($paginated_images as $imagem): ?>
                            <a href="<?php echo htmlspecialchars($imagem); ?>" data-fancybox="gallery" class="photo-item">
                                <img src="<?php echo htmlspecialchars($imagem); ?>" alt="Foto do evento">
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Este evento não possui fotos na galeria.</p>
                    <?php endif; ?>
                </div>

                <?php if($total_pages > 1): ?>
                <div class="pagination">
                    <?php if($current_page > 1): ?>
                        <a href="?id=<?php echo $event_id; ?>&page=<?php echo $current_page - 1; ?>">Página anterior</a>
                    <?php endif; ?>

                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?id=<?php echo $event_id; ?>&page=<?php echo $i; ?>" class="<?php echo $i == $current_page ? 'current' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if($current_page < $total_pages): ?>
                        <a href="?id=<?php echo $event_id; ?>&page=<?php echo $current_page + 1; ?>">Próxima página</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            </div>

            <aside class="event-sidebar">
                <div class="sidebar-widget">
                    <h3>Próximos eventos</h3>
                    <p>Acompanhe nossa agenda</p>
                    <ul>
                        <?php if(!empty($proximos_eventos)): ?>
                            <?php foreach($proximos_eventos as $prox_evento): ?>
                                <li><a href="evento-detalhe.php?id=<?php echo $prox_evento['id']; ?>"><?php echo htmlspecialchars($prox_evento['title']); ?></a></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>Nenhum outro evento agendado.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </aside>
        </div>

    <?php else: ?>
        <section class="secao-introducao">
            <div class="container" style="text-align: center;"><h2>Evento não encontrado</h2></div>
        </section>
    <?php endif; ?>
</main>

<?php include 'partials/footer.php'; ?>