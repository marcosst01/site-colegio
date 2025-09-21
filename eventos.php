<?php
// define o título da página antes de incluir o header
$page_title = "Eventos - Colégio Monteiro Lobato";
include 'partials/header.php';

$events_file = 'data/events.json';
$all_events = file_exists($events_file) ? json_decode(file_get_contents($events_file), true) : [];

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

    <section class="events-page-section">
        <div class="container">
            <div class="events-grid">
                <?php if (empty($all_events)): ?>
                    <p>Nenhum evento agendado no momento.</p>
                <?php else: foreach($all_events as $event): ?>
                    <?php
                        // escolhe a imagem de capa / fallback e garante saída segura
                        if (!empty($event['cover_image'])) {
                            $cover_image = htmlspecialchars($event['cover_image'], ENT_QUOTES, 'UTF-8');
                        } elseif (!empty($event['images']) && is_array($event['images'])) {
                            $cover_image = htmlspecialchars($event['images'][0], ENT_QUOTES, 'UTF-8');
                        } else {
                            $cover_image = 'img/placeholder.png';
                        }

                        $event_title = htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8');
                        $event_date = !empty($event['date']) ? date('d/m/Y', strtotime($event['date'])) : '';
                    ?>
                    <a href="evento-detalhe.php?id=<?php echo urlencode($event['id']); ?>" class="event-card" aria-label="<?php echo $event_title; ?>">
                        <img src="<?php echo $cover_image; ?>" alt="<?php echo $event_title; ?>" title="<?php echo $event_title . ($event_date ? ' — ' . $event_date : ''); ?>">
                        <div class="event-caption">
                            <p><?php echo $event_title; ?></p>
                            <span style="font-size: 0.8em; display: block; margin-top: 5px; color: #555;"><?php echo $event_date; ?></span>
                        </div>
                    </a>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </section>

    <section class="secao-espacos" style="background-color: #f8f9fa;">
      <div class="container">
        <h2>Nossos Espaços</h2>
        <p class="subtitulo-espacos">Ambientes seguros e estimulantes, pensados para cada fase do desenvolvimento.</p>
        <div class="espacos-grid">
          <div class="espaco-foto">
            <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0MzczODV8MHwxfHNlYXJjaHwxfHxTYWxhJTIwZGUlMjBhdWxhJTIwZGUlMjBjcmVhbsOnYXN8ZW58MHx8fHwxNzI1NDM0OTg1fDA&ixlib=rb-4.0.3&q=80&w=1080" alt="Sala de aula colorida e organizada">
            <span>Salas temáticas</span>
          </div>
          <div class="espaco-foto">
            <img src="https://images.unsplash.com/photo-1612871689353-cccf581d0d6b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0MzczODV8MHwxfHNlYXJjaHwxfHxQYXJxdWUlMjBlc2NvbGFyfGVufDB8fHx8MTcyNTQzNTAwNnww&ixlib=rb-4.0.3&q=80&w=1080" alt="Playground ao ar livre com brinquedos">
            <span>Parque externo</span>
          </div>
          <div class="espaco-foto">
            <img src="https://images.unsplash.com/photo-1594732386927-75a7c92a92e3?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0MzczODV8MHwxfHNlYXJjaHwxfHxIb3J0YSUyMGVzY29sYXJ8ZW58MHx8fHwxNzI1NDM1MDIxfDA&ixlib=rb-4.0.3&q=80&w=1080" alt="Crianças cuidando de uma horta na escola">
            <span>Horta pedagógica</span>
          </div>
          <div class="espaco-foto">
            <img src="https://images.unsplash.com/photo-1543421276-f5664a78087a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0MzczODV8MHwxfHNlYXJjaHwxfHxCaWJsaW90ZWNhJTIwSW5mYW50aWx8ZW58MHx8fHwxNzI1NDM1MDQwfDA&ixlib=rb-4.0.3&q=80&w=1080" alt="Crianças sentadas em um cantinho de leitura">
            <span>Cantinho da leitura</span>
          </div>
        </div>
      </div>
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

</main>

<?php include 'partials/footer.php'; ?>
