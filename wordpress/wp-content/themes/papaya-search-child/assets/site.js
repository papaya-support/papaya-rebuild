(() => {
  'use strict';
  const toggle = document.querySelector('.menu-toggle');
  const navigation = document.getElementById('primary-navigation');
  if (toggle && navigation) {
    toggle.hidden = false;
    navigation.classList.add('is-collapsible');
  }
  const submenuControls = [];
  navigation?.querySelectorAll('.menu-item-has-children').forEach((item, index) => {
    const link = item.querySelector(':scope > a');
    const submenu = item.querySelector(':scope > .sub-menu');
    if (!link || !submenu) return;

    const button = document.createElement('button');
    const label = link.textContent.trim();
    submenu.id ||= `primary-submenu-${index + 1}`;
    button.type = 'button';
    button.className = 'submenu-toggle';
    button.setAttribute('aria-controls', submenu.id);
    button.innerHTML = '<span aria-hidden="true">⌄</span>';
    const setOpen = (open) => {
      item.classList.toggle('submenu-open', open);
      button.setAttribute('aria-expanded', String(open));
      button.setAttribute('aria-label', `${open ? 'Close' : 'Open'} ${label} submenu`);
      if (!open) {
        submenuControls.forEach((control) => {
          if (submenu.contains(control.button)) control.setOpen(false);
        });
      }
    };
    button.addEventListener('click', () => setOpen(button.getAttribute('aria-expanded') !== 'true'));
    link.after(button);
    setOpen(false);
    submenuControls.push({ item, button, setOpen });
    item.addEventListener('keydown', (event) => {
      if (event.key !== 'Escape' || button.getAttribute('aria-expanded') !== 'true') return;
      event.preventDefault();
      event.stopPropagation();
      setOpen(false);
      button.focus();
    });
    item.addEventListener('focusout', (event) => {
      if (!item.contains(event.relatedTarget)) setOpen(false);
    });
  });
  navigation?.classList.add('has-submenu-controls');
  document.addEventListener('click', (event) => {
    if (navigation && !navigation.contains(event.target)) {
      submenuControls.forEach((control) => control.setOpen(false));
    }
  });

  toggle?.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') !== 'true';
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
    navigation.classList.toggle('is-open', open);
    if (!open) submenuControls.forEach((control) => control.setOpen(false));
  });
  document.querySelectorAll('.faq-item').forEach((item) =>
    item.addEventListener('toggle', () => {
      if (item.open)
        document.querySelectorAll('.faq-item').forEach((other) => {
          if (other !== item) other.open = false;
        });
    }),
  );
  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    document.querySelectorAll('.faq-item[open]').forEach((item) => {
      item.open = false;
    });
    if (toggle?.getAttribute('aria-expanded') === 'true') {
      toggle.click();
      toggle.focus();
    }
  });
  const more = document.querySelector('[data-view-more]');
  const grid = document.querySelector('[data-blog-grid]');
  const status = document.querySelector('.blog-status');
  let loading = false;
  more?.addEventListener('click', async (event) => {
    if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || event.button) return;
    event.preventDefault();
    if (loading || !grid) return;
    loading = true;
    more.setAttribute('aria-disabled', 'true');
    grid.setAttribute('aria-busy', 'true');
    status.textContent = 'Loading more posts…';
    try {
      const response = await fetch(more.href, { credentials: 'same-origin' });
      if (!response.ok) throw new Error('Unable to load posts');
      const nextPage = new DOMParser().parseFromString(await response.text(), 'text/html');
      const nextGrid = nextPage.querySelector('[data-blog-grid]');
      if (!nextGrid || !nextPage.querySelector('footer')) throw new Error('Incomplete page');
      const existing = new Set(
        [...grid.querySelectorAll('[data-post-id]')].map((card) => card.dataset.postId),
      );
      const cards = [...nextGrid.querySelectorAll('.post-card')].filter(
        (card) => !existing.has(card.dataset.postId),
      );
      grid.append(...cards);
      const next = nextPage.querySelector('[data-view-more]');
      if (next) more.href = next.href;
      else more.hidden = true;
      status.textContent = next ? `${cards.length} more posts loaded.` : 'All posts loaded.';
      if (event.detail === 0) cards[0]?.querySelector('h2 a')?.focus({ preventScroll: true });
    } catch {
      status.textContent = 'Unable to load more posts. Please try again.';
    } finally {
      loading = false;
      more.removeAttribute('aria-disabled');
      grid.removeAttribute('aria-busy');
    }
  });
})();
