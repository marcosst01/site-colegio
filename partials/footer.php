<footer class="site-footer">
    <div class="footer-container">

        <div class="footer-col">
            <img src="Img/logo.monteiro.portrait.c2f2c9532219.svg" alt="Logo Colégio Monteiro Lobato" class="footer-logo">
            <p class="footer-about">Compromisso com uma educação de excelência, promovendo a autonomia e o desenvolvimento integral dos estudantes.</p>
        </div>

        <div class="footer-col">
            <h4>Central de atendimento</h4>
            <ul class="contact-list">
                <li>
                    <i class="fas fa-phone"></i>
                    <a href="tel:+557532810614">(75) 3281-0614</a>
                </li>
                <li>
                    <i class="fab fa-whatsapp"></i>
                    <a href="https://wa.me/5575988544276" target="_blank" rel="noopener noreferrer">
                        (75) 98854-4276
                    </a>
                 <li>
                    <a href="mailto:secretariamonteiro@gmail.com">
                        <i class="fas fa-envelope"></i>
                        <span>secretariamonteiro@gmail.com</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Mapa do Site</h4>
            <ul class="footer-nav">
                <li><a href="#">O Colégio</a></li>
                <li><a href="#">Educação Infantil</a></li>
                <li><a href="#">Fundamental I e II</a></li>
                <li><a href="#">Eventos</a></li>
                <li><a href="#">Jornal Monteiro</a></li>
                <li><a href="#">Pré Matrícula</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Siga-nos</h4>
            <div class="footer-social-icons">
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>

    </div>

    <div class="footer-bottom-bar">
        <p>© 2025 Colégio Monteiro Lobato - Todos os direitos reservados.</p>
    </div>
</footer>
   
    
    <script src="js/script.js"></script>
    <script src="js/slide.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/depoimentos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

    <script>
        Fancybox.bind("[data-fancybox]", {
            // Suas opções personalizadas podem ir aqui
        });
    </script>

    <script>
  // Seleciona elementos
  const hamburger = document.querySelector(".hamburger");
  const navMenu = document.querySelector(".nav-menu");
  const hasSubmenus = document.querySelectorAll(".has-submenu");

  // Abre/fecha o menu hamburguer
  hamburger.addEventListener("click", () => {
    navMenu.classList.toggle("active");
    hamburger.classList.toggle("open");
  });

  // Abre/fecha submenus no mobile
  hasSubmenus.forEach(item => {
    item.addEventListener("click", e => {
      // Só no mobile
      if (window.innerWidth <= 1024) {
        e.preventDefault(); // Evita redirecionamento
        const submenu = item.querySelector(".submenu");
        submenu.classList.toggle("open");
      }
    });
  });

  // Fecha menu ao clicar fora (opcional)
  document.addEventListener("click", (e) => {
    if (!navMenu.contains(e.target) && !hamburger.contains(e.target)) {
      navMenu.classList.remove("active");
      hamburger.classList.remove("open");
    }
  });
</script>

</body>
</html>