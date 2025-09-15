// Aguarda o conteúdo da página carregar para executar o script
document.addEventListener('DOMContentLoaded', () => {

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

    const videoThumbnails = document.querySelectorAll('.video-container-popup');
    const modal = document.getElementById('video-modal');
    const modalCloseBtn = document.querySelector('.modal-close');
    const modalVideoContainer = document.getElementById('modal-video-container');

    const openModal = (youtubeId) => {
        swiper.autoplay.stop();
        modalVideoContainer.innerHTML = `<iframe src="https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
        modal.classList.add('visible');
    };

    const closeModal = () => {
        modal.classList.remove('visible');
        modalVideoContainer.innerHTML = '';
        swiper.autoplay.start();
    };

    videoThumbnails.forEach(thumb => {
        thumb.addEventListener('click', () => {
            const youtubeId = thumb.dataset.youtubeId;
            openModal(youtubeId);
        });
    });

    modalCloseBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

});