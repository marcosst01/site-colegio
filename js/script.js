// Captura o formulário de contato pelo ID
document.getElementById('form-contato').addEventListener('submit', function(e) {
  // Evita que a página recarregue ao enviar o formulário
  e.preventDefault();

  // Exibe uma mensagem de alerta simulando envio
  alert('Mensagem enviada! Entraremos em contato em breve.');
});