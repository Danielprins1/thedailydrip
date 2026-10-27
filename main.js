/* The Daily Drip — scroll & parallax
   Heavy scroll = Lenis with a low lerp (lower = heavier/slower to settle).
   All scroll animations are GSAP ScrollTrigger with scrub. */

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
gsap.registerPlugin(ScrollTrigger);

/* ---------- Heavy smooth scroll ---------- */
if (!reduceMotion) {
  const lenis = new Lenis({
    lerp: 0.055,          // 0.1 = default; 0.04–0.06 feels heavy. Tune here.
    wheelMultiplier: 0.8, // less distance per wheel tick = more weight
    smoothWheel: true,
  });
  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add((t) => lenis.raf(t * 1000));
  gsap.ticker.lagSmoothing(0);

  // anchor links go through Lenis so they glide too
  document.querySelectorAll('a[href^="#"]').forEach((a) => {
    a.addEventListener('click', (e) => {
      const target = document.querySelector(a.getAttribute('href'));
      if (target) { e.preventDefault(); lenis.scrollTo(target, { duration: 2.2 }); }
    });
  });
}

/* ---------- Logo fade-in ---------- */
gsap.fromTo('.logo',
  { opacity: 0, y: -10, filter: 'blur(8px)' },
  { opacity: 1, y: 0, filter: 'blur(0px)', duration: 1.8, delay: 0.3, ease: 'power3.out' }
);
gsap.from('.corner, .hero__coords', { opacity: 0, y: 20, duration: 1.4, delay: 0.9, stagger: 0.15, ease: 'power3.out' });

if (!reduceMotion) {
  /* ---------- 1. Hero: card rises through a slow background ---------- */
  const hero = { trigger: '.hero', start: 'top top', end: 'bottom bottom', scrub: true };
  gsap.to('.hero__card', {
    y: () => -(window.innerHeight + document.querySelector('.hero__card').offsetHeight + 60),
    ease: 'none', scrollTrigger: { ...hero, invalidateOnRefresh: true },
  });
  gsap.to('.hero__bg', { yPercent: 8, scale: 1.08, ease: 'none', scrollTrigger: hero });
  gsap.to('.corner', { y: -60, opacity: 0, ease: 'none',
    scrollTrigger: { trigger: '.hero', start: '60% bottom', end: 'bottom bottom', scrub: true } });

  /* ---------- 2. Mission: words light up one by one ---------- */
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

  /* ---------- 3. Statement: bg slow, each line at its own speed ---------- */
  gsap.to('.statement__bg', { yPercent: 18, ease: 'none',
    scrollTrigger: { trigger: '.statement', start: 'top bottom', end: 'bottom top', scrub: true } });
  gsap.utils.toArray('.statement__title .line').forEach((line, i) => {
    gsap.from(line, { y: 120 + i * 70, ease: 'none',
      scrollTrigger: { trigger: '.statement', start: 'top bottom', end: 'top top', scrub: true } });
  });
  gsap.from('.statement__side', { y: 200, ease: 'none',
    scrollTrigger: { trigger: '.statement', start: 'top 60%', end: 'center center', scrub: true } });
  gsap.from('.card', { y: 140, stagger: 0.08, ease: 'none',
    scrollTrigger: { trigger: '.cards', start: 'top bottom', end: 'bottom 85%', scrub: true } });

  /* ---------- 4. Stack: photos slide up and land on top of each other ---------- */
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

  /* ---------- 5/6. Catering heading + footer wordmark ---------- */
  gsap.from('.catering__text h2', { y: 120, ease: 'none',
    scrollTrigger: { trigger: '.catering', start: 'top bottom', end: 'center center', scrub: true } });
  gsap.from('.footer__word', { yPercent: 60, ease: 'none',
    scrollTrigger: { trigger: '.footer', start: 'top bottom', end: 'bottom bottom', scrub: true } });
}

window.addEventListener('load', () => ScrollTrigger.refresh());
