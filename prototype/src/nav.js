const MOBILE_QUERY = '(max-width: 767px)';

export function initNav(root = document) {
  const page = root.body?.dataset.page;
  if (page) {
    root.querySelectorAll(`[data-nav="${page}"]`).forEach((link) => link.setAttribute('aria-current', 'page'));
  }

  const toggle = root.querySelector('.nav-toggle');
  const menu = root.getElementById('primary-menu');
  if (!toggle || !menu) return;

  const setOpen = (open) => {
    toggle.setAttribute('aria-expanded', String(open));
    menu.classList.toggle('is-open', open);
  };

  toggle.addEventListener('click', () => setOpen(toggle.getAttribute('aria-expanded') !== 'true'));

  root.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
      setOpen(false);
      toggle.focus();
    }
  });

  menu.addEventListener('click', (event) => {
    if (event.target.closest('a')) setOpen(false);
  });

  window.matchMedia(MOBILE_QUERY).addEventListener('change', () => setOpen(false));
}

initNav();
