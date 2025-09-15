// Inicialização do Swiper
const swiper = new Swiper('.meu-slider-depoimentos', {
    effect: 'fade',
    fadeEffect: {
        crossFade: true
    },
    speed: 1500,
    loop: true,
    grabCursor: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});


// --- Lógica do Modal (Pop-up) ---

// 1. Seleciona os elementos do DOM
const videoThumbnails = document.querySelectorAll('.video-container-popup');
const modal = document.getElementById('video-modal');
const modalCloseBtn = document.querySelector('.modal-close');
const modalVideoContainer = document.getElementById('modal-video-container');

// 2. Função para abrir o modal
const openModal = (youtubeId) => {
    // Para o autoplay do carrossel
    swiper.autoplay.stop();
    
    // Cria o iframe com autoplay
    modalVideoContainer.innerHTML = `<iframe src="https://www.youtube.com/embed/${youtubeId}?autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
    
    // Mostra o modal
    modal.classList.add('visible');
};

// 3. Função para fechar o modal
const closeModal = () => {
    // Esconde o modal
    modal.classList.remove('visible');

    // Remove o iframe para parar o vídeo
    modalVideoContainer.innerHTML = '';
    
    // Retoma o autoplay do carrossel
    swiper.autoplay.start();
};

// 4. Adiciona os eventos de clique
// Para cada thumbnail de vídeo
videoThumbnails.forEach(thumb => {
    thumb.addEventListener('click', () => {
        const youtubeId = thumb.dataset.youtubeId;
        openModal(youtubeId);
    });
});

// Para o botão de fechar
modalCloseBtn.addEventListener('click', closeModal);

// Para fechar o modal clicando fora do vídeo (no fundo escuro)
modal.addEventListener('click', (event) => {
    if (event.target === modal) {
        closeModal();
    }
});