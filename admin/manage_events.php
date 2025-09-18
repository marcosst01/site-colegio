<?php
include 'auth.php';

$events_file = '../data/events.json';

// Função para ler eventos
function getEvents() {
    global $events_file;
    if (!file_exists($events_file)) return [];
    return json_decode(file_get_contents($events_file), true) ?? [];
}

// Função para salvar eventos
function saveEvents($events) {
    global $events_file;
    file_put_contents($events_file, json_encode($events, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Processamento do formulário (Adicionar ou Editar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $events = getEvents();
    $id = $_POST['id'] ? (int)$_POST['id'] : time();
    $existing_images = [];

    if ($_POST['id']) {
        foreach ($events as $event) {
            if ($event['id'] == $id) {
                $existing_images = $event['images'] ?? [];
                break;
            }
        }
    }

    if (!empty($_POST['delete_images'])) {
        foreach ($_POST['delete_images'] as $image_to_delete) {
            if (($key = array_search($image_to_delete, $existing_images)) !== false) {
                unset($existing_images[$key]);
                if (file_exists('../' . $image_to_delete)) @unlink('../' . $image_to_delete);
            }
        }
        $existing_images = array_values($existing_images);
    }

    $newly_uploaded_images = [];
    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        $upload_dir = '../uploads/';
        foreach ($_FILES['images']['name'] as $key => $name) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $image_name = uniqid('gallery_') . '-' . basename($name);
                $target_file = $upload_dir . $image_name;
                if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $target_file)) {
                    $newly_uploaded_images[] = 'uploads/' . $image_name;
                }
            }
        }
    }
    
    $all_images = array_merge($existing_images, $newly_uploaded_images);

    // LÓGICA DA FOTO DE CAPA
    $cover_image = $_POST['cover_image'] ?? '';
    if (empty($cover_image) && !empty($all_images)) {
        $cover_image = $all_images[0]; // Pega a primeira como padrão se nenhuma for escolhida
    }

    $event_data = [
        'id' => $id,
        'title' => $_POST['title'],
        'date' => $_POST['date'],
        'category' => $_POST['category'],
        'description' => $_POST['description'],
        'cover_image' => $cover_image, // <-- NOVO CAMPO SALVO
        'images' => $all_images
    ];

    $found = false;
    if ($_POST['id']) {
        foreach ($events as $key => $event) {
            if ($event['id'] == $id) {
                $events[$key] = $event_data;
                $found = true;
                break;
            }
        }
    } 
    
    if (!$found) {
        array_unshift($events, $event_data);
    }
    
    saveEvents($events);
    header('Location: manage_events.php');
    exit;
}

// Restante do código (Deletar, Carregar para editar, etc.)
if (isset($_GET['delete'])) { /* ...código de deletar... */ }
$all_events = getEvents();
$edit_event = null;
if (isset($_GET['edit'])) { /* ...código para encontrar o evento a ser editado... */ }
// CÓDIGO COMPLETO ABAIXO PARA GARANTIA
if (isset($_GET['delete'])) {
    $events = getEvents();
    $id_to_delete = (int)$_GET['delete'];
    $events_to_keep = [];
    foreach($events as $event){
        if($event['id'] != $id_to_delete){
            $events_to_keep[] = $event;
        } else {
            if (!empty($event['images'])) {
                foreach ($event['images'] as $img) {
                    if (file_exists('../' . $img)) @unlink('../' . $img);
                }
            }
        }
    }
    saveEvents($events_to_keep);
    header('Location: manage_events.php');
    exit;
}
$all_events = getEvents();
$edit_event = null;
if (isset($_GET['edit'])) {
    $id_to_edit = (int)$_GET['edit'];
    foreach($all_events as $event) {
        if ($event['id'] == $id_to_edit) {
            $edit_event = $event;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Gerenciar Eventos</title><link rel="stylesheet" href="style.css">
    <style>
        .image-gallery{display:flex;flex-wrap:wrap;gap:20px;margin-top:10px;}
        .image-item{text-align:center;border:1px solid #ddd;padding:10px;border-radius:5px;}
        .image-item label{display:block;margin-top:5px;font-size:0.9em;}
    </style>
</head>
<body>
<div class="admin-container">
    <header class="admin-header">
        <h1>Gerenciar Eventos</h1>
        <nav>
    <a href="index.php">Dashboard</a>
    <a href="manage_slides.php">Gerenciar Slides</a> <a href="manage_news.php">Gerenciar Notícias</a>
    <a href="manage_events.php">Gerenciar Eventos</a>
    <a href="manage_testimonials.php">Gerenciar Depoimentos</a>
    <a href="manage_tv.php">Gerenciar TV Monteiro</a>
    <a href="logout.php" class="logout-btn">Sair</a>
</nav>
    </header>
    <main>
        <div class="form-container">
            <h2><?php echo $edit_event ? 'Editar Evento' : 'Adicionar Novo Evento'; ?></h2>
            <form method="POST" action="manage_events.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $edit_event['id'] ?? ''; ?>">
                <div class="input-group"><label for="title">Título</label><input type="text" id="title" name="title" value="<?php echo htmlspecialchars($edit_event['title'] ?? ''); ?>" required></div>
                <div class="input-group"><label for="date">Data</label><input type="date" id="date" name="date" value="<?php echo htmlspecialchars($edit_event['date'] ?? ''); ?>" required></div>
                <div class="input-group"><label for="category">Categoria</label><select id="category" name="category" required>
                    <option value="Geral" <?php echo ($edit_event['category'] ?? '') == 'Geral' ? 'selected' : ''; ?>>Geral</option>
                    <option value="Educação Infantil" <?php echo ($edit_event['category'] ?? '') == 'Educação Infantil' ? 'selected' : ''; ?>>Educação Infantil</option>
                    <option value="Fundamental 1" <?php echo ($edit_event['category'] ?? '') == 'Fundamental 1' ? 'selected' : ''; ?>>Fundamental 1</option>
                    <option value="Fundamental 2" <?php echo ($edit_event['category'] ?? '') == 'Fundamental 2' ? 'selected' : ''; ?>>Fundamental 2</option>
                </select></div>
                <div class="input-group"><label for="description">Descrição</label><textarea id="description" name="description" rows="6"><?php echo htmlspecialchars($edit_event['description'] ?? ''); ?></textarea></div>
                <div class="input-group"><label for="images">Adicionar Novas Fotos</label><input type="file" id="images" name="images[]" multiple accept="image/*"></div>

                <?php if ($edit_event && !empty($edit_event['images'])): ?>
                <div class="input-group">
                    <label>Gerenciar Fotos Atuais</label>
                    <div class="image-gallery">
                        <?php foreach($edit_event['images'] as $image): ?>
                        <div class="image-item">
                            <img src="../<?php echo htmlspecialchars($image); ?>" width="120">
                            <label><input type="radio" name="cover_image" value="<?php echo htmlspecialchars($image); ?>" <?php echo ($edit_event['cover_image'] ?? $edit_event['images'][0]) == $image ? 'checked' : ''; ?>> Definir como Capa</label>
                            <label><input type="checkbox" name="delete_images[]" value="<?php echo htmlspecialchars($image); ?>"> Excluir</label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <button type="submit"><?php echo $edit_event ? 'Atualizar Evento' : 'Publicar Evento'; ?></button>
                <?php if ($edit_event): ?><a href="manage_events.php" class="cancel-btn">Cancelar Edição</a><?php endif; ?>
            </form>
        </div>
        <div class="content-list">
             <h2>Eventos Publicados</h2>
            <table>
                <thead><tr><th>Capa</th><th>Título</th><th>Categoria</th><th>Data</th><th>Ações</th></tr></thead>
                <tbody>
                    <?php if (empty($all_events)): ?>
                        <tr><td colspan="5">Nenhum evento publicado ainda.</td></tr>
                    <?php else: foreach($all_events as $event): 
                        $cover = $event['cover_image'] ?? ($event['images'][0] ?? '');
                    ?>
                        <tr>
                            <td><img src="../<?php echo htmlspecialchars($cover); ?>" width="80"></td>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo htmlspecialchars($event['category'] ?? 'Geral'); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($event['date'])); ?></td>
                            <td class="actions">
                                <a href="manage_events.php?edit=<?php echo $event['id']; ?>" class="edit-btn">Editar</a>
                                <a href="manage_events.php?delete=<?php echo $event['id']; ?>" class="delete-btn" onclick="return confirm('Tem certeza?');">Excluir</a>
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