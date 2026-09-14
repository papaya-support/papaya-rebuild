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
  const filters = [...document.querySelectorAll('[data-filter]')];
  const cards = [...document.querySelectorAll('.post-card[data-category]')];
  const status = document.querySelector('.blog-status');
  filters.forEach((button, index) => button.addEventListener('click', () => {
    const category = button.dataset.filter;
    filters.forEach(other => other.setAttribute('aria-pressed', String(other === button)));
    cards.forEach(card => { card.hidden = index !== 0 && card.dataset.category !== category; });
    const count = cards.filter(card => !card.hidden).length;
    if (status) status.textContent = count ? `${count} posts in ${category}.` : `No posts in ${category} yet.`;
  }));
  document.querySelector('[data-view-more]')?.addEventListener('click', () => {
    if (status) status.textContent = 'You’re viewing all available posts.';
  });
})();
