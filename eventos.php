<?php 
include 'partials/header.php'; 

$events_file = 'data/events.json';
$all_events = file_exists($events_file) ? json_decode(file_get_contents($events_file), true) : [];

// LÓGICA PARA ORDENAR POR DATA
if (!empty($all_events)) {
    usort($all_events, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
}
?>
<main>
    <section class="banner-pagina" style="background-image: linear-gradient(rgba(0, 51, 102, 0.6), rgba(0, 51, 102, 0.6)), url('Img/site 01.jpg');">
        <div class="container">
            <h1>Nossos Eventos</h1>
            <p>Confira os momentos que marcam nosso ano letivo</p>
        </div>
    </section>

    <section class="events-section" style="background-color: #fff; padding-top: 60px;">
        <div class="container" style="max-width: 1200px; margin: 0 auto;">
            <h2 class="section-title" style="color: #003366;">Todos os <strong>Eventos</strong></h2>
            <div class="events-grid">

                <?php if (empty($all_events)): ?>
                    <p>Nenhum evento agendado no momento.</p>
                <?php else: ?>
                    <?php foreach($all_events as $event): ?>
    <?php
        // LÓGICA ATUALIZADA DA FOTO DE CAPA
        $cover_image = !empty($event['cover_image']) ? htmlspecialchars($event['cover_image']) : (!empty($event['images']) ? htmlspecialchars($event['images'][0]) : 'img/placeholder.png');
    ?>
    <a href="evento-detalhe.php?id=<?php echo $event['id']; ?>" class="event-card">
        <img src="<?php echo $cover_image; ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
        <div class="event-caption">
            <p><?php echo htmlspecialchars($event['title']); ?></p>
            <span style="font-size: 0.8em; display: block; margin-top: 5px; color: #555;"><?php echo date('d/m/Y', strtotime($event['date'])); ?></span>
        </div>
    </a>
<?php endforeach; ?>
                <?php endif; // <-- ESTA LINHA ESTAVA FALTANDO ?>

            </div>
        </div>
    </section>
</main>

<?php include 'partials/footer.php'; ?>