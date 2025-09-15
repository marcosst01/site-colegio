<?php
$events_file = '../data/events.json';

if (!file_exists($events_file)) {
    die("Arquivo events.json não encontrado.\n");
}

$data = json_decode(file_get_contents($events_file), true);

// Se o JSON estiver vazio ou inválido
if (!is_array($data)) {
    die("Erro: events.json está vazio ou corrompido.\n");
}

$updated = false;

// Percorre todos os eventos e adiciona o campo "date" se não existir
foreach ($data as &$event) {
    if (!isset($event['date'])) {
        $event['date'] = ""; // deixa em branco para não quebrar
        $updated = true;
    }
}

if ($updated) {
    file_put_contents(
        $events_file,
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
    echo "✅ Todos os eventos antigos foram atualizados com o campo 'date'.\n";
} else {
    echo "ℹ️ Todos os eventos já tinham o campo 'date'. Nada foi alterado.\n";
}
