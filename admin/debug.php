<?php
echo "<h1>Teste de Diagnóstico do Painel</h1>";

// 1. Checar versão do PHP
echo "<h2>1. Versão do PHP</h2>";
if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
    echo "<p style='color:green;'>OK! Versão do PHP: " . PHP_VERSION . " (Compatível)</p>";
} else {
    echo "<p style='color:red;'>Atenção! Sua versão do PHP é muito antiga e pode não funcionar.</p>";
}

// 2. Checar se o arquivo users.json existe e pode ser lido
echo "<h2>2. Leitura do Arquivo users.json</h2>";
$file_path = '../data/users.json';
if (file_exists($file_path)) {
    echo "<p style='color:green;'>OK! Arquivo encontrado em: " . realpath($file_path) . "</p>";
    $users_json = file_get_contents($file_path);
    $users = json_decode($users_json, true);

    // 3. Checar se o JSON é válido
    echo "<h2>3. Validade do JSON</h2>";
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "<p style='color:green;'>OK! O conteúdo do arquivo users.json é um JSON válido.</p>";
        echo "<h3>Conteúdo lido do arquivo:</h3>";
        echo "<pre>";
        print_r($users);
        echo "</pre>";
    } else {
        echo "<p style='color:red;'>ERRO! O conteúdo do arquivo users.json parece estar corrompido. Tente copiar e colar o código original novamente.</p>";
    }
} else {
    echo "<p style='color:red;'>ERRO! Arquivo não encontrado. Verifique se a pasta /data e o arquivo /data/users.json existem.</p>";
}

// 4. Teste final da senha
echo "<h2>4. Teste de Verificação da Senha</h2>";
if (function_exists('password_verify')) {
    $password_correta = 'mudar123';
    $hash_do_arquivo = '$2y$10$g6N.g83E5/1oR.o.X.AbJOa3wKGgqgDEc53PU2Q/75z5.yK1/d4.m';
    
    if (isset($users[0]['password'])) {
       $hash_do_arquivo = $users[0]['password'];
       echo "<p>Hash sendo testado: " . htmlspecialchars($hash_do_arquivo) . "</p>";
    }

    if (password_verify($password_correta, $hash_do_arquivo)) {
        echo "<p style='color:green; font-weight:bold; font-size: 20px;'>SUCESSO! A senha 'mudar123' corresponde ao código no arquivo. O problema deve ser apenas a digitação na tela de login.</p>";
    } else {
        echo "<p style='color:red; font-weight:bold; font-size: 20px;'>FALHA! A senha 'mudar123' NÃO corresponde ao código no arquivo. Isso indica que o conteúdo do users.json foi alterado. Por favor, copie e cole o código original novamente.</p>";
    }
} else {
     echo "<p style='color:red;'>ERRO GRAVE! A função password_verify não existe no seu PHP. É necessário atualizar o XAMPP.</p>";
}
?>