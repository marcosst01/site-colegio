// js/slide.js ATUALIZADO
// A linha "const TOTAL_SLIDES = 6;" foi REMOVIDA.

var cont = 1;

// A variável TOTAL_SLIDES agora vem do arquivo index.php

document.getElementById('radio1').checked = true;

// Autoplay
setInterval(() => {
    proximaImg();
}, 5000);

function proximaImg() {
    cont++;
    if (cont > TOTAL_SLIDES) {
        cont = 1;
    }
    document.getElementById('radio' + cont).checked = true;
}

// Botão de avançar
function avancarSlide() {
  cont++;
  if (cont > TOTAL_SLIDES) {
      cont = 1;
  }
  document.getElementById('radio' + cont).checked = true;
}

// Botão de voltar
function voltarSlide() {
  cont--;
  if (cont < 1) {
      cont = TOTAL_SLIDES;
  }
  document.getElementById('radio' + cont).checked = true;
}