<?php
include 'auth.php';

$slides_file = '../data/slides.json';

function getSlides() {
    global $slides_file;
    if (!file_exists($slides_file)) return [];
    return json_decode(file_get_contents($slides_file), true) ?? [];
}

function saveSlides($slides) {
    global $slides_file;
    file_put_contents($slides_file, json_encode($slides, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function handleImageUpload($file_input_name, $prefix) {
    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $image_name = uniqid($prefix) . '-' . basename($_FILES[$file_input_name]['name']);
        $target_file = $upload_dir . $image_name;
        if (move_uploaded_file($_FILES[$file_input_name]['tmp_name'], $target_file)) {
            return 'uploads/' . $image_name;
        }
    }
    return null;
}

// Adicionar ou Editar Slide
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alt_text'])) {
    $slides = getSlides();
    $id = $_POST['id'] ? (int)$_POST['id'] : time();

    $desktop_image = handleImageUpload('desktop_image', 'desktop_') ?? $_POST['existing_desktop_image'];
    $mobile_image = handleImageUpload('mobile_image', 'mobile_') ?? $_POST['existing_mobile_image'];

    $slide_data = [
        'id' => $id,
        'desktop_image' => $desktop_image,
        'mobile_image' => $mobile_image,
        'alt_text' => $_POST['alt_text'],
        'link_url' => $_POST['link_url']
    ];

    $found = false;
    if ($_POST['id']) {
        foreach ($slides as $key => $slide) {
            if ($slide['id'] == $id) {
                $slides[$key] = $slide_data;
                $found = true;
                break;
            }
        }
    }
    if (!$found) {
        // Adiciona novos slides no final para manter a ordem
        $slides[] = $slide_data;
    }
    
    saveSlides($slides);
    header('Location: manage_slides.php');
    exit;
}

// Deletar Slide
if (isset($_GET['delete'])) {
    $slides = getSlides();
    $id_to_delete = (int)$_GET['delete'];
    $slides = array_filter($slides, fn($slide) => $slide['id'] != $id_to_delete);
    saveSlides(array_values($slides));
    header('Location: manage_slides.php');
    exit;
}

$all_slides = getSlides();
$edit_slide = null;
if (isset($_GET['edit'])) {
    $id_to_edit = (int)$_GET['edit'];
    foreach($all_slides as $slide) {
        if ($slide['id'] == $id_to_edit) {
            $edit_slide = $slide;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Gerenciar Slides</title><link rel="stylesheet" href="style.css">
</head>
<body>
<div class="admin-container">
    <header class="admin-header">
        <h1>Gerenciar Slides da Home</h1>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="manage_news.php">Gerenciar Notícias</a>
            <a href="manage_events.php">Gerenciar Eventos</a>
            <a href="manage_testimonials.php">Gerenciar Depoimentos</a>
            <a href="logout.php" class="logout-btn">Sair</a>
        </nav>
    </header>
    <main>
        <div class="form-container">
            <h2><?php echo $edit_slide ? 'Editar Slide' : 'Adicionar Novo Slide'; ?></h2>
            <form method="POST" action="manage_slides.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $edit_slide['id'] ?? ''; ?>">
                <input type="hidden" name="existing_desktop_image" value="<?php echo $edit_slide['desktop_image'] ?? ''; ?>">
                <input type="hidden" name="existing_mobile_image" value="<?php echo $edit_slide['mobile_image'] ?? ''; ?>">
                
                <div class="input-group">
                    <label for="desktop_image">Imagem para Desktop (Tamanho recomendado: 1600x533px)</label>
                    <input type="file" id="desktop_image" name="desktop_image" accept="image/*" <?php echo $edit_slide ? '' : 'required'; ?>>
                    <?php if ($edit_slide && !empty($edit_slide['desktop_image'])): ?><p>Atual: <img src="../<?php echo htmlspecialchars($edit_slide['desktop_image']); ?>" width="150"></p><?php endif; ?>
                </div>

                <div class="input-group">
                    <label for="mobile_image">Imagem para Celular (Tamanho recomendado: 1040x600px)</label>
                    <input type="file" id="mobile_image" name="mobile_image" accept="image/*" <?php echo $edit_slide ? '' : 'required'; ?>>
                    <?php if ($edit_slide && !empty($edit_slide['mobile_image'])): ?><p>Atual: <img src="../<?php echo htmlspecialchars($edit_slide['mobile_image']); ?>" width="150"></p><?php endif; ?>
                </div>

                <div class="input-group">
                    <label for="alt_text">Texto Alternativo (Descrição da imagem)</label>
                    <input type="text" id="alt_text" name="alt_text" value="<?php echo htmlspecialchars($edit_slide['alt_text'] ?? ''); ?>" required>
                </div>

                <div class="input-group">
                    <label for="link_url">Link (Opcional, para onde o slide aponta ao ser clicado)</label>
                    <input type="url" id="link_url" name="link_url" value="<?php echo htmlspecialchars($edit_slide['link_url'] ?? ''); ?>" placeholder="https://...">
                </div>

                <button type="submit"><?php echo $edit_slide ? 'Atualizar Slide' : 'Publicar Slide'; ?></button>
                <?php if ($edit_slide): ?><a href="manage_slides.php" class="cancel-btn">Cancelar Edição</a><?php endif; ?>
            </form>
        </div>
        <div class="content-list">
            <h2>Slides Publicados</h2>
            <table>
                <thead><tr><th>Desktop</th><th>Mobile</th><th>Texto Alternativo</th><th>Ações</th></tr></thead>
                <tbody>
                    <?php if (empty($all_slides)): ?>
                        <tr><td colspan="4">Nenhum slide publicado.</td></tr>
                    <?php else: foreach($all_slides as $slide): ?>
                        <tr>
                            <td><img src="../<?php echo htmlspecialchars($slide['desktop_image']); ?>" width="100"></td>
                            <td><img src="../<?php echo htmlspecialchars($slide['mobile_image']); ?>" width="100"></td>
                            <td><?php echo htmlspecialchars($slide['alt_text']); ?></td>
                            <td class="actions">
                                <a href="manage_slides.php?edit=<?php echo $slide['id']; ?>" class="edit-btn">Editar</a>
                                <a href="manage_slides.php?delete=<?php echo $slide['id']; ?>" class="delete-btn" onclick="return confirm('Tem certeza?');">Excluir</a>
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