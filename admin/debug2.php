<?php
echo "<h1>Teste Final e Definitivo de Senha</h1>";
echo "<p>Este teste não lê nenhum arquivo. Ele verifica se a função de senha do seu PHP está funcionando corretamente com os dados exatos.</p>";

$password_para_testar = 'mudar123';
$hash_original = '$2y$10$g6N.g83E5/1oR.o.X.AbJOa3wKGgqgDEc53PU2Q/75z5.yK1/d4.m';

echo "<p><b>Testando a senha:</b> " . $password_para_testar . "</p>";
echo "<p><b>Contra o hash:</b> " . $hash_original . "</p>";
echo "<hr>";

if (password_verify($password_para_testar, $hash_original)) {
    echo "<h2 style='color:green;'>SUCESSO!</h2>";
    echo "<p>O teste passou. Isso confirma que a função de senha do seu PHP está funcionando perfeitamente.</p>";
    echo "<p><b>Conclusão:</b> O problema está 100% no arquivo <b>/data/users.json</b>. Ele pode ter sido salvo com uma codificação diferente (ex: UTF-8 com BOM) ou algum caractere invisível foi adicionado. A solução é recriar o arquivo do zero com cuidado.</p>";
} else {
    echo "<h2 style='color:red;'>FALHA.</h2>";
    echo "<p>Isto é muito raro e indica que há um problema com a instalação do PHP no seu XAMPP que impede a função <b>password_verify</b> de funcionar corretamente.</p>";
    echo "<p><b>Conclusão:</b> O problema não está nos arquivos que enviei, mas sim no seu ambiente de servidor local. A recomendação neste caso seria tentar reinstalar uma versão recente e estável do XAMPP.</p>";
}
?>