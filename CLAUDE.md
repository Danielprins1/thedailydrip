# The Daily Drip — new website

Daniël (CMD student, freelance designer, DJP Design) is building a new website for client **The Daily Drip (TDD)**, a coffee & lunch spot in Amsterdam Zuidas. Communicate with Daniël in **Dutch**. Site copy is in **English** for now (NL toggle planned).

## Client facts
- Antonio Vivaldistraat 58, 1083 HP Amsterdam — Mon–Fri 08:00–16:00 — info@thedailydrip.nl
- Current site: https://thedailydrip.nl (WordPress/Elementor, one long page, menu as PDF)
- Audience: Zuidas office workers. Catering for offices is a key business line.
- Brand: hand-drawn "THE DAILY DRIP" logo, warm golden yellow, cream, black. Illustrations exist (yellow car "BiroKoffieNew", koffievrouw line drawing). Style: minimal, bold, playful.

## Design direction
Visual reference: **escape.cafe** (Montreal coffee brand). Take the *feel*, not the content:
- **Heavy scroll** — Lenis with low `lerp` (0.055) + `wheelMultiplier` 0.8. Tune in `main.js`.
- **Lots of parallax** — full-bleed background photos moving slower than content; photos stacked on top of each other at different speeds.
- Huge condensed display type ("KEEP THE GRIND GOING"), small condensed caps in corners, light large body text for the mission.
- Fixed white sidebar on the right: hamburger, vertical ticker text, catering icon, language toggle.
- **Logo fades in** on load (fixed top-left). Logo file still to come from Daniël → replace `.logo__placeholder` span with `<img src="img/logo.svg">`.

## Stack
Plain HTML/CSS/JS, no build step. CDN: Lenis 1.1.13, GSAP 3.12.5 + ScrollTrigger. Fonts: Big Shoulders Display (display), Inter Tight (body).
Run locally: `npx serve .` or `python3 -m http.server`.

## Structure (index.html)
1. **Hero** — 260vh, sticky; bg photo zooms slowly, portrait photo card rises through; corner texts; coordinates.
2. **Mission** — grey-blue; text lights up word by word on scroll; 3 floating photos with `data-speed` parallax.
3. **Statement / menu** — huge title lines with different speeds over parallax bg; side text + SEE MENU; 4 menu cards (prices are placeholders `€ –,–`).
4. **Stack** — 400vh, sticky; 4 photos slide up and land on top of each other with slight rotation ("A day at the Drip", 08:00–15:30).
5. **Catering** — yellow, "FUEL THE DAILY DRIP", mailto CTA.
6. **Footer** — visit / hours / contact + giant wordmark.

## Images
Put photos in `img/` with these names (placeholders show the expected filename until the file exists):
hero-bg.jpg, hero-card.jpg, mission-1..3.jpg, statement-bg.jpg (could become a video), day-1..4.jpg, catering.jpg, logo.svg.

## Open / to do
- [ ] Logo in + fade-in check
- [ ] Real photos (Daniël has them) — compress to ~200–400 KB, webp
- [ ] Verify the coordinates in the hero (`52.3366° N — 4.8726° E` is approximate)
- [ ] Real menu items + prices → ideally an HTML menu page instead of the PDF
- [ ] Brand yellow hex from the brand file (now `#F2B632` placeholder)
- [ ] Sidebar menu overlay, NL/EN toggle, Instagram link
- [ ] Catering form (date, people, company) instead of mailto
- [ ] Optional: statement background as looping video (like the reference's wave)
- [ ] SEO: LocalBusiness structured data, OG image
