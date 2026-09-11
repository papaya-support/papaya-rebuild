(() => {
 'use strict';
 document.querySelector('.menu-toggle')?.addEventListener('click',e=>{const b=e.currentTarget;const open=b.getAttribute('aria-expanded')!=='true';b.setAttribute('aria-expanded',String(open));document.getElementById('mobile-navigation').hidden=!open;});
 document.querySelectorAll('.faq-question').forEach(b=>b.addEventListener('click',()=>{
  const open=b.getAttribute('aria-expanded')!=='true';
  document.querySelectorAll('.faq-question').forEach(other=>{other.setAttribute('aria-expanded','false');document.getElementById(other.getAttribute('aria-controls')).hidden=true;});
  b.setAttribute('aria-expanded',String(open));document.getElementById(b.getAttribute('aria-controls')).hidden=!open;
 }));
 document.addEventListener('keydown',e=>{if(e.key==='Escape'){document.querySelectorAll('.faq-question[aria-expanded=true]').forEach(b=>b.click());const m=document.querySelector('.menu-toggle[aria-expanded=true]');if(m)m.click();}});
 document.querySelector('.skip-link')?.addEventListener('click',e=>{if(matchMedia('(max-width:767px)').matches){e.currentTarget.href='#mobile-main';document.getElementById('mobile-main')?.setAttribute('tabindex','-1');}});
})();
(() => {
 const source=document.getElementById('papaya-blog-cards');if(!source)return;
 const cards=JSON.parse(source.textContent), status=document.querySelector('.blog-status');
 const stage=document.querySelector('.xd-stage');
 const nodesFor=card=>[...card.fields.flatMap(key=>[...stage.querySelectorAll(`[data-field="${key}"]`)]),...stage.querySelectorAll(`[data-image="${card.image}"]`)];
 document.querySelectorAll('[data-filter]').forEach(button=>button.addEventListener('click',()=>{
  const category=button.dataset.filter;let count=0;
  document.querySelectorAll('[data-filter]').forEach(b=>b.setAttribute('aria-pressed',String(b.dataset.filter===category)));
  cards.forEach(card=>{
   const visible=category==='View All'||card.category===category;
   nodesFor(card).forEach(n=>{n.classList.toggle('is-filtered-out',!visible);if(visible)n.dataset.slot=String(count);});
   card.fields.forEach(key=>document.querySelectorAll(`.mobile-text[data-field="${key}"]`).forEach(n=>{const parent=n.closest('.mobile-column');if(parent)parent.hidden=!visible;}));
   if(visible)count++;
  });
  status.textContent=count?`${count} posts in ${category}.`:`No posts in ${category} yet.`;
  status.classList.add('is-visible');
 }));
 document.querySelectorAll('[data-view-more]').forEach(button=>button.addEventListener('click',()=>{
  status.textContent='You’re viewing all available posts.';status.classList.add('is-visible');
 }));
})();
