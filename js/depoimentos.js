// Aguarda o conteúdo da página carregar para executar o script
document.addEventListener('DOMContentLoaded', () => {

    // Inicialização do Swiper para os depoimentos
    // (Este código continua aqui para não quebrar a outra seção)
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

    // SELETOR ATUALIZADO PARA INCLUIR OS VÍDEOS DA LISTA DA TV MONTEIRO
    const videoThumbnails = document.querySelectorAll('.video-container-popup, .video-list-item');
    
    const modal = document.getElementById('video-modal');
    const modalCloseBtn = document.querySelector('.modal-close');
    const modalVideoContainer = document.getElementById('modal-video-container');

    const openModal = (youtubeId) => {
        // Para a reprodução de qualquer slider na página ao abrir o vídeo
        if (swiper && swiper.autoplay && swiper.autoplay.running) {
            swiper.autoplay.stop();
        }
        modalVideoContainer.innerHTML = `<iframe src="https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
        modal.classList.add('visible');
    };

    const closeModal = () => {
        modal.classList.remove('visible');
        modalVideoContainer.innerHTML = '';
        // Reinicia a reprodução do slider de depoimentos (se existir) ao fechar o vídeo
        if (swiper && swiper.autoplay) {
            swiper.autoplay.start();
        }
    };

    videoThumbnails.forEach(thumb => {
        thumb.addEventListener('click', (e) => {
            e.preventDefault(); // Impede que o link '#' da lista de vídeos recarregue a página
            const youtubeId = thumb.dataset.youtubeId;
            if (youtubeId) {
                openModal(youtubeId);
            }
        });
    });

    modalCloseBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
});