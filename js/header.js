// Shrink header ao rolar
window.addEventListener('scroll', function () {
  const header = document.querySelector('.main-header');
  if (window.scrollY > 50) {
    header.classList.add('shrink');
  } else {
    header.classList.remove('shrink');
  }
});

// Hamburger + menu mobile
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');
const submenuParents = document.querySelectorAll('.has-submenu > a');

hamburger.addEventListener('click', () => {
  navMenu.classList.toggle('active');
  hamburger.classList.toggle('active');
});

// Submenus no mobile (abre/fecha com clique)
submenuParents.forEach(parentLink => {
  parentLink.addEventListener('click', e => {
    if (window.innerWidth <= 768) {
      e.preventDefault();
      const submenu = parentLink.nextElementSibling;
      if (submenu.classList.contains('open')) {
        submenu.classList.remove('open');
        parentLink.querySelector('.arrow-icon').style.transform = 'rotate(0deg)';
      } else {
        submenu.classList.add('open');
        parentLink.querySelector('.arrow-icon').style.transform = 'rotate(180deg)';
      }
    }
  });
});
