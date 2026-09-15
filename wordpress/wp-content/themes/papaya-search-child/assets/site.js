(() => {
  'use strict';
  const toggle = document.querySelector('.menu-toggle');
  const navigation = document.getElementById('primary-navigation');
  toggle?.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') !== 'true';
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
    navigation.classList.toggle('is-open', open);
  });
  document.querySelectorAll('.faq-item').forEach(item => item.addEventListener('toggle', () => {
    if (item.open) document.querySelectorAll('.faq-item').forEach(other => { if (other !== item) other.open = false; });
  }));
  document.addEventListener('keydown', event => {
    if (event.key !== 'Escape') return;
    document.querySelectorAll('.faq-item[open]').forEach(item => { item.open = false; });
    if (toggle?.getAttribute('aria-expanded') === 'true') toggle.click();
  });
})();
