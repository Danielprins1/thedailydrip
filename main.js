/* The Daily Drip — scroll & parallax
   Heavy scroll = Lenis with a low lerp (lower = heavier/slower to settle).
   All scroll animations are GSAP ScrollTrigger with scrub.
   Shared by every page: each block only runs when its section exists. */

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const $ = (s) => document.querySelector(s);
gsap.registerPlugin(ScrollTrigger);

/* ---------- Heavy smooth scroll ---------- */
let lenis = null;
if (!reduceMotion) {
  lenis = new Lenis({
    lerp: 0.055,          // 0.1 = default; 0.04–0.06 feels heavy. Tune here.
    wheelMultiplier: 0.8, // less distance per wheel tick = more weight
    smoothWheel: true,
  });
  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add((t) => lenis.raf(t * 1000));
  gsap.ticker.lagSmoothing(0);
}

/* ---------- Menu overlay (opened from the sidebar or the pill) ---------- */
const menuBtns = document.querySelectorAll('.js-menu-btn');
const menu = $('.menu');
const setMenu = (open) => {
  document.body.classList.toggle('menu-open', open);
  menuBtns.forEach((b) => {
    b.setAttribute('aria-expanded', open);
    b.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
  });
  menu.setAttribute('aria-hidden', !open);
  if (lenis) open ? lenis.stop() : lenis.start();
  if (open) gsap.fromTo('.menu__list li', { yPercent: 110 }, { yPercent: 0, duration: 0.9, stagger: 0.06, ease: 'power4.out', delay: 0.15 });
};
menuBtns.forEach((b) => b.addEventListener('click', () => setMenu(!document.body.classList.contains('menu-open'))));
document.addEventListener('keydown', (e) => { if (e.key === 'Escape') setMenu(false); });

/* ---------- Sidebar → pill nav ----------
   On load you see the escape-style sidebar + big logo; then the sidebar slides out,
   the page widens and the LAP-style pill drops in. Tune the delay here. */
const PILL_DELAY = reduceMotion ? 0 : 2200;
setTimeout(() => {
  document.body.classList.add('nav-pill');
  setTimeout(() => ScrollTrigger.refresh(), reduceMotion ? 0 : 1000); // page got wider
}, PILL_DELAY);

/* Same-page anchors glide through Lenis (also from inside the menu) */
document.querySelectorAll('a[href*="#"]').forEach((a) => {
  a.addEventListener('click', (e) => {
    const url = new URL(a.href);
    const page = (p) => p.replace(/index\.html$/, '');
    if (page(url.pathname) !== page(location.pathname)) return;
    const target = url.hash && $(url.hash);
    setMenu(false);
    if (target && lenis) { e.preventDefault(); lenis.scrollTo(target, { duration: 2.2 }); }
  });
});

/* ---------- Forms: open a prefilled email (no backend yet) ---------- */
document.querySelectorAll('.js-mailform').forEach((form) => {
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const body = [...new FormData(form)].filter(([, v]) => v).map(([k, v]) => `${k}: ${v}`).join('\n');
    location.href = `mailto:info@thedailydrip.nl?subject=${encodeURIComponent(form.dataset.subject)}&body=${encodeURIComponent(body)}`;
  });
});

/* ---------- Logo turns dark over light sections ---------- */
document.querySelectorAll('[data-logo="dark"]').forEach((sec) => {
  ScrollTrigger.create({ trigger: sec, start: 'top 70px', end: 'bottom 70px', toggleClass: { targets: '.logo', className: 'logo--dark' } });
});

/* ---------- Logo fade-in ---------- */
gsap.fromTo('.logo',
  { opacity: 0, y: -10, filter: 'blur(8px)' },
  { opacity: 1, y: 0, filter: 'blur(0px)', duration: 1.8, delay: 0.3, ease: 'power3.out' }
);
gsap.from('.corner, .hero__coords', { opacity: 0, y: 20, duration: 1.4, delay: 0.9, stagger: 0.15, ease: 'power3.out' });

if (!reduceMotion) {
  /* ---------- Home 1. Hero: card rises through a slow background ---------- */
  if ($('.hero')) {
    const hero = { trigger: '.hero', start: 'top top', end: 'bottom bottom', scrub: true };
    gsap.to('.hero__card', {
      y: () => -(window.innerHeight + $('.hero__card').offsetHeight + 60),
      ease: 'none', scrollTrigger: { ...hero, invalidateOnRefresh: true },
    });
    gsap.to('.hero__bg', { yPercent: 8, scale: 1.08, ease: 'none', scrollTrigger: hero });
    gsap.to('.hero .corner', { y: -60, opacity: 0, ease: 'none',
      scrollTrigger: { trigger: '.hero', start: '60% bottom', end: 'bottom bottom', scrub: true } });
  }

  /* ---------- Sub page hero: title lines rise in, bg drifts ---------- */
  if ($('.page-hero')) {
    gsap.from('.page-hero__title .line', { yPercent: 100, opacity: 0, duration: 1.4, stagger: 0.1, delay: 0.4, ease: 'power4.out' });
    gsap.to('.page-hero__bg', { yPercent: 15, scale: 1.06, ease: 'none',
      scrollTrigger: { trigger: '.page-hero', start: 'top top', end: 'bottom top', scrub: true } });
    gsap.utils.toArray('.page-hero__title .line').forEach((line, i) => {
      gsap.to(line, { y: -(80 + i * 60), ease: 'none',
        scrollTrigger: { trigger: '.page-hero', start: 'top top', end: 'bottom top', scrub: true } });
    });
  }

  /* ---------- Words light up one by one ---------- */
  document.querySelectorAll('.js-words').forEach((el) => {
    [...el.childNodes].forEach((node) => {
      if (node.nodeType !== Node.TEXT_NODE) return;
      const frag = document.createDocumentFragment();
      node.textContent.split(/(\s+)/).forEach((part) => {
        if (!part.trim()) { frag.append(part); return; }
        const s = document.createElement('span');
        s.className = 'w'; s.textContent = part; frag.append(s);
      });
      node.replaceWith(frag);
    });
    gsap.to(el.querySelectorAll('.w'), {
      opacity: 1, stagger: 0.05, ease: 'none',
      scrollTrigger: { trigger: el, start: 'top 75%', end: 'bottom 40%', scrub: true },
    });
  });

  /* ---------- Generic parallax: any element with data-speed ---------- */
  document.querySelectorAll('[data-speed]').forEach((el) => {
    const speed = parseFloat(el.dataset.speed);
    gsap.fromTo(el, { y: () => -speed * window.innerHeight * 0.5 }, {
      y: () => speed * window.innerHeight * 0.5, ease: 'none',
      scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: true, invalidateOnRefresh: true },
    });
  });

  /* ---------- Home 3. Statement: bg slow, each line at its own speed ---------- */
  if ($('.statement')) {
    gsap.to('.statement__bg', { yPercent: 18, ease: 'none',
      scrollTrigger: { trigger: '.statement', start: 'top bottom', end: 'bottom top', scrub: true } });
    gsap.utils.toArray('.statement__title .line').forEach((line, i) => {
      gsap.from(line, { y: 120 + i * 70, ease: 'none',
        scrollTrigger: { trigger: '.statement', start: 'top bottom', end: 'top top', scrub: true } });
    });
    gsap.from('.statement__side', { y: 200, ease: 'none',
      scrollTrigger: { trigger: '.statement', start: 'top 60%', end: 'center center', scrub: true } });
  }

  /* ---------- Company coffee: beans bg + title lines ---------- */
  if ($('.beans')) {
    gsap.to('.beans__bg', { yPercent: 18, ease: 'none',
      scrollTrigger: { trigger: '.beans', start: 'top bottom', end: 'bottom top', scrub: true } });
    gsap.utils.toArray('.beans__title .line').forEach((line, i) => {
      gsap.from(line, { y: 140 + i * 90, ease: 'none',
        scrollTrigger: { trigger: '.beans', start: 'top bottom', end: 'top top', scrub: true } });
    });
  }

  /* ---------- Menu / bean cards rise in a stagger ---------- */
  document.querySelectorAll('.cards').forEach((group) => {
    gsap.from(group.querySelectorAll('.card'), { y: 140, stagger: 0.08, ease: 'none',
      scrollTrigger: { trigger: group, start: 'top bottom', end: 'bottom 85%', scrub: true } });
  });

  /* ---------- Steps / courses / doors slide up one after another ---------- */
  ['.step', '.course', '.door'].forEach((sel) => {
    gsap.utils.toArray(sel).forEach((el, i) => {
      gsap.from(el, { y: 80 + (i % 3) * 60, ease: 'none',
        scrollTrigger: { trigger: el, start: 'top bottom', end: 'top 55%', scrub: true } });
    });
  });

  /* ---------- Home 4. Stack: photos slide up and land on top of each other ---------- */
  if ($('.stack')) {
    const cards = gsap.utils.toArray('.stack__card');
    gsap.set(cards.slice(1), { y: () => window.innerHeight * 1.1 });
    const rot = [-3, 2.5, -1.5, 3];
    const stackTl = gsap.timeline({
      scrollTrigger: { trigger: '.stack', start: 'top top', end: 'bottom bottom', scrub: true, invalidateOnRefresh: true },
    });
    cards.forEach((card, i) => {
      if (i === 0) return;
      stackTl
        .to(card, { y: 0, rotation: rot[i % rot.length], ease: 'none' }, i - 1)
        .to(cards.slice(0, i), { scale: '-=0.04', filter: 'brightness(0.85)', ease: 'none' }, i - 1);
    });
  }

  /* ---------- Big headings + footer wordmark ---------- */
  document.querySelectorAll('.catering__text h2, .order__intro h2').forEach((h) => {
    gsap.from(h, { y: 120, ease: 'none',
      scrollTrigger: { trigger: h.closest('section'), start: 'top bottom', end: 'center center', scrub: true } });
  });
  gsap.from('.footer__word', { yPercent: 60, ease: 'none',
    scrollTrigger: { trigger: '.footer', start: 'top bottom', end: 'bottom bottom', scrub: true } });
}

window.addEventListener('load', () => ScrollTrigger.refresh());
