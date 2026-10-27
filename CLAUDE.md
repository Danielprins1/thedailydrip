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
- Fixed white sidebar on the right: hamburger, vertical ticker text, catering icon, language toggle — shown only on load.
- **Sidebar → pill nav**: ~2.2 s after load (`PILL_DELAY` in `main.js`) the sidebar slides out, the page widens and a LAP-style floating dark pill drops in at the top (logo, hamburger, Menu / Company coffee / Training / Visit, NL, yellow "Order catering" CTA). Current page link is yellow. On mobile the pill keeps logo, hamburger and CTA.
- **Logo fades in** on load (fixed top-left), then hands over to the logo inside the pill. Uses `img/logo.svg` (black SVG, made white with `filter: invert(1)`; turns black over sections marked `data-logo="dark"`). The text wordmark only shows while the file is missing.
- Second reference: **lap.coffee** — Daniël loves its navigation (floating rounded pill: logo, hamburger, links, yellow CTA) and overall UX/styling. (Not reachable from the cloud sandbox; work from Daniël's screenshots.)

## Stack
Plain HTML/CSS/JS, no build step. CDN: Lenis 1.1.13, GSAP 3.12.5 + ScrollTrigger. Fonts: Big Shoulders Display (display), Inter Tight (body).
Run locally: `npx serve .` or `python3 -m http.server`.

## Pages
All pages share the logo, the pill nav, the menu overlay (hamburger → full-screen dark list, numbered, big display type), the sidebar, the footer and `main.js` (each animation block only runs when its section exists).
- `index.html` — home (below)
- `company-coffee.html` — "Company coffee / order for your machine": hero, intro, 3 steps, bean cards, order form
- `training.html` — coffee training: hero, intro, course list (photo appears on hover), photo strip, booking form
Forms (`.js-mailform`) open a prefilled email to info@thedailydrip.nl — no backend yet.

## Structure (index.html)
1. **Hero** — 260vh, sticky; bg photo zooms slowly, portrait photo card rises through; corner texts; coordinates.
2. **Mission** — grey-blue; text lights up word by word on scroll; 3 floating photos with `data-speed` parallax.
3. **Statement / menu** — huge title lines with different speeds over parallax bg; side text + SEE MENU; 4 menu cards (prices are placeholders `€ –,–`).
4. **Stack** — 400vh, sticky; 4 photos slide up and land on top of each other with slight rotation ("A day at the Drip", 08:00–15:30).
4b. **Doors** — two big photo cards linking to Company coffee and Coffee training.
5. **Catering** — yellow, "FUEL THE DAILY DRIP", mailto CTA.
6. **Footer** — visit / hours / contact + giant wordmark.

## Images
Put photos in `img/` with these names (placeholders show the expected filename until the file exists):
- Home: hero-bg.jpg, hero-card.jpg, mission-1..3.jpg, statement-bg.jpg (could become a video), day-1..4.jpg, door-company.jpg, door-training.jpg, catering.jpg, logo.svg
- Company coffee: company-bg.jpg, beans-bg.jpg
- Training: training-bg.jpg, course-basics.jpg, course-latteart.jpg, course-brew.jpg, course-team.jpg, training-1..3.jpg

## Open / to do
- [x] Logo in + fade-in check
- [ ] Real photos (Daniël has them) — compress to ~200–400 KB, webp
- [ ] Verify the coordinates in the hero (`52.3366° N — 4.8726° E` is approximate)
- [ ] Real menu items + prices → ideally an HTML menu page instead of the PDF
- [ ] Brand yellow hex from the brand file (now `#F2B632` placeholder)
- [x] Sidebar menu overlay + LAP-style pill nav
- [ ] NL/EN toggle, Instagram link
- [ ] Real bean names/prices (company coffee) and course prices/durations (training)
- [ ] Forms → real form handling (Formspree / Netlify Forms) instead of mailto
- [ ] Catering form (date, people, company) instead of mailto
- [ ] Optional: statement background as looping video (like the reference's wave)
- [ ] SEO: LocalBusiness structured data, OG image
