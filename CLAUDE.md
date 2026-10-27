# The Daily Drip — new website

Daniël (CMD student, freelance designer, DJP Design) is building a new website for client **The Daily Drip (TDD)**, a coffee & lunch spot in Amsterdam Zuidas. Communicate with Daniël in **Dutch**. Site copy is in **English** for now (NL toggle planned).

## Client facts
- Antonio Vivaldistraat 58, 1083 HP Amsterdam — Mon–Fri 08:00–16:00 — info@thedailydrip.nl
- Current site: https://thedailydrip.nl (WordPress/Elementor, one long page, menu as PDF)
- Audience: Zuidas office workers. Catering for offices is a key business line.
- Brand: hand-drawn "THE DAILY DRIP" logo. Colours: **yellow `#ECB43F`** (C2 M34 Y95 K0), **black**, **white** — nothing else (greys only for photo placeholders). Illustrations exist (yellow car "BiroKoffieNew", koffievrouw line drawing). Style: minimal, bold, playful.

## Design direction
Visual reference: **escape.cafe** (Montreal coffee brand). Take the *feel*, not the content:
- **Heavy scroll** — Lenis with low `lerp` (0.055) + `wheelMultiplier` 0.8. Tune in `main.js`.
- **Lots of parallax** — full-bleed background photos moving slower than content; photos stacked on top of each other at different speeds.
- Font: **Coolvetica** (Typodermic) for headings/labels, Inter Tight for body. Headings in sentence case, weight 400 (no faux bold).
- **Not brutalist**: Daniël found the first version too in-your-face. Keep type calm and orderly (`clamp()` max ~168px for the biggest titles), rounded corners (`--radius`), pill-shaped buttons, label column + content column grids. Small uppercase only for labels/corners.
- Fixed white sidebar on the right: hamburger, vertical ticker text, language toggle (no bag/cart icon — Daniël asked to remove it everywhere) — shown only while the first section is on screen. On phones it is a bottom bar (hamburger + NL).
- **Menu** links (pill + overlay) open the menu PDF in a new tab.
- **Phones/tablets run a lite mode** (`lite` in `main.js`): native scroll (no Lenis), no parallax layers or slide-ins — only hero card, word light-up and the nav. Keep it that way; Daniël found it janky on his phone.
- White text on photos: photos get a dark gradient (`::after`) + text-shadow. Check readability whenever a photo changes.
- **Sidebar ↔ pill nav**: once the next section fills half the screen (`PILL_AT` in `main.js`) the sidebar slides out, the page widens and a LAP-style floating dark pill rises in, **fixed at the bottom of the screen** (logo, hamburger, Menu / Company coffee / Training / Visit, NL, yellow "Order catering" CTA). Scrolling back up reverses it. Current page link is yellow. On mobile the pill keeps logo, hamburger and CTA.
- **Logo fades in** on load (fixed top-left), then flies (moves + shrinks) into its slot in the pill and hands over to the pill's own logo. Uses `img/logo.svg` (black SVG, made white with `filter: invert(1)`; turns black over sections marked `data-logo="dark"`). The text wordmark only shows while the file is missing.
- Second reference: **lap.coffee** — Daniël loves its navigation (floating rounded pill: logo, hamburger, links, yellow CTA) and overall UX/styling. (Not reachable from the cloud sandbox; work from Daniël's screenshots.)

## Stack
Plain HTML/CSS/JS, no build step. CDN: Lenis 1.1.13, GSAP 3.12.5 + ScrollTrigger. Fonts: Coolvetica via `@font-face` (`local()` first, then `fonts/coolvetica-rg.woff2` / `.otf` — Daniël still needs to add these files), Inter Tight from Google Fonts.
Run locally: `npx serve .` or `python3 -m http.server`.

## Pages
All pages share the logo, the pill nav, the menu overlay (hamburger → full-screen dark list, numbered, big display type), the sidebar, the footer and `main.js` (each animation block only runs when its section exists).
- `index.html` — home (below)
- `company-coffee.html` — "Company coffee / order for your machine": hero, intro, 3 steps, bean cards, order form
- `training.html` — coffee training: hero, intro, course list (photo appears on hover), photo strip, booking form
Forms (`.js-mailform`) open a prefilled email to info@thedailydrip.nl — no backend yet.

## Structure (index.html)
1. **Hero** — 240vh, sticky; bg photo zooms slowly, portrait photo card rises through; corner texts.
2. **Mission** — white; text lights up word by word on scroll; 3 floating photos with `data-speed` parallax.
3. **Statement** — "Keep the grind going" over parallax bg; side text + See menu (PDF).
4. **Doors** — two big photo cards linking to Company coffee and Coffee training.
5. **Catering** — yellow, "FUEL THE DAILY DRIP", mailto CTA.
6. **Footer** — visit / hours / contact + logo.

Removed on Daniël's request — don't bring back: the "A day at the Drip" stacked-photo section (felt like Tinder cards), the 4 menu cards on home (Flat white, Sandwich of the day…) and the hero coordinates. He does like the card styling (`.cards` / `.card`) — reuse it on a future menu page.

## Images
Photos live in `img/` as **.webp** (~100–250 KB, max 2000px wide; full-bleed backgrounds also have a 1000px `-sm.webp` used via `srcset`). CSS/JS links carry `?v=N` — bump it when you change them so browsers/GitHub Pages don't serve a stale copy. Placeholders show the expected filename until the file exists. Filled so far from Daniël's first 5 photos (latte art top-down, storefront, interior seating, baguettes, bar counter) — mission-2/3 and the door images are crops of those and should be replaced by real photos:
- Home: all filled (hero-bg, hero-card, mission-1..3, statement-bg, door-company, door-training, catering, logo.svg)
- Company coffee: all filled (company-bg, beans-bg = beans close-up)
- Training: all filled (training-bg, course-*, training-1..3 = iced latte pour, latte + yellow lamp, can sealer)
- Spare: `drinks.webp` (TDD juice cans) — for a future menu page.

## Open / to do
- [x] Logo in + fade-in check
- [x] Photos: every slot filled (22 TDD photos so far)
- [ ] Real menu items + prices → ideally an HTML menu page instead of the PDF
- [x] Brand yellow from the brand file (`#ECB43F`)
- [ ] Coolvetica font files in `fonts/` (coolvetica-rg.woff2 + .otf) so visitors without the font see it
- [x] Sidebar menu overlay + LAP-style pill nav
- [ ] NL/EN toggle, Instagram link
- [ ] Real bean names/prices (company coffee) and course prices/durations (training)
- [ ] Forms → real form handling (Formspree / Netlify Forms) instead of mailto
- [ ] Catering form (date, people, company) instead of mailto
- [ ] Optional: statement background as looping video (like the reference's wave)
- [ ] SEO: LocalBusiness structured data, OG image
