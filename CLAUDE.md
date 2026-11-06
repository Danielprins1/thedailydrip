# The Daily Drip - new website

Daniël (CMD student, freelance designer, DJP Design) is building a new website for client **The Daily Drip (TDD)**, a coffee & lunch spot in Amsterdam Zuidas. Communicate with Daniël in **Dutch**. Site copy is **English by default with a working NL toggle**.

## Client facts
- Antonio Vivaldistraat 58, 1083 HP Amsterdam - Mon–Fri 08:00–16:00 - info@thedailydrip.nl
- Current site: https://thedailydrip.nl (WordPress/Elementor, one long page, menu as PDF)
- Audience: Zuidas office workers. Catering for offices is a key business line.
- Brand: hand-drawn "THE DAILY DRIP" logo. Colours: **yellow `#ECB43F`** (C2 M34 Y95 K0), **black**, **white** - nothing else (greys only for photo placeholders). Illustrations exist (yellow car "BiroKoffieNew", koffievrouw line drawing). Style: minimal, bold, playful.

## Design direction
Visual reference: **escape.cafe** (Montreal coffee brand). Take the *feel*, not the content:
- **Heavy scroll** - Lenis with low `lerp` (0.055) + `wheelMultiplier` 0.8. Tune in `main.js`.
- **Lots of parallax** - full-bleed background photos moving slower than content; photos stacked on top of each other at different speeds.
- Font: **Coolvetica** (Typodermic) for headings/labels, Inter Tight for body. Headings in sentence case, weight 400 (no faux bold).
- **Not brutalist**: Daniël found the first version too in-your-face. Keep type calm and orderly (`clamp()` max ~168px for the biggest titles), rounded corners (`--radius`), pill-shaped buttons, label column + content column grids. Small uppercase only for labels/corners.
- **Menu** is an HTML page (`menu.html`), no PDF anymore. Menu copy is exactly as Daniël supplied it (casing, spelling). **No prices on the site** (Daniël removed them) - don't rewrite or translate it.
- **Phones/tablets run a lite mode** (`lite` in `main.js`): native scroll (no Lenis), no parallax layers or slide-ins - only hero card, word light-up and the nav. Keep it that way; Daniël found it janky on his phone. Scroll-linked animations use `SCRUB` (0.6 smoothing on phones, `true` on desktop) so the hero card doesn't jitter against native scroll; the pill check reads no layout while scrolling. Because nothing moves there, layouts must work static: the mission photos sit in a grid on phones (not on top of each other), and every `.media` has `isolation: isolate` so its image never covers the text (without GSAP transforms the statement photo used to hide the title).
- White text on photos: photos get a dark gradient (`::after`) + text-shadow. Check readability whenever a photo changes.
- **Pill nav** (LAP-style): a floating dark pill **fixed at the bottom of the screen on every page from the start** (rises in on load): logo, Home / Menu, NL, yellow "Company coffee" CTA. Current page link is yellow. **Hamburger only on phones** (≤760px; on desktop it was double) - phones show logo, hamburger, CTA; the overlay lists Home / Menu / Company coffee. **No sidebar** - Daniël removed the escape-style white side bar.
- **Logo fades in** on load (fixed, **top centre, large**: clamp(72px, 8vw, 120px), 64px on phones) and simply **fades out** once the next section fills half the screen (`PILL_AT` in `main.js`; `past-hero` class), fades back in when scrolling up. Don't animate it across the screen - Daniël found the flying logo ugly. Uses `img/logo.svg` (black SVG, made white with `filter: invert(1)`; turns black over sections marked `data-logo="dark"`). The text wordmark only shows while the file is missing. Under the logo sits the payoff **"Specialty Coffee and Sandwiches"** (`.logo__payoff`, small caps, English on both languages) - **home only**, and it fades out (`payoff-out`) as soon as the next section peeks in, before the logo itself goes. Payoff font: **Arpona Sans** (Adobe Fonts, family `arpona-sans`, Bold 700) - loaded via Daniël's Adobe Fonts web project, kit `https://use.typekit.net/gxb6fin.css` (linked in index.html only), never self-hosted (Typekit licence; his .otf files came from a mirror site). The kit only works on the domains listed in that Adobe project.
- Second reference: **lap.coffee** - Daniël loves its navigation (floating rounded pill: logo, hamburger, links, yellow CTA) and overall UX/styling. (Not reachable from the cloud sandbox; work from Daniël's screenshots.)

## Stack
Plain HTML/CSS/JS, no build step. CDN: Lenis 1.1.13, GSAP 3.12.5 + ScrollTrigger. Fonts: Coolvetica self-hosted (`fonts/coolvetica-rg.woff2` + `-it.woff2`, converted from Daniël's .otf; he also has Cond, Cram and Heavy Comp if needed), Inter Tight from Google Fonts.
Run locally: `npx serve .` or `python3 -m http.server`.

## Language (NL/EN)
- English is the element's content; Dutch sits next to it in a `data-nl="…"` attribute (HTML-escaped; may contain markup such as `<br>` or the `.tag` link). **Every new piece of copy needs a `data-nl` too.**
- Form label text is wrapped in `<span data-nl>`; `<option>`s and `<title>` have `data-nl`. Brand slogans ("hustle, drip, sip", "more espresso, less depresso") stay English.
- `.js-lang` buttons toggle: `pill__lang` (pill, hidden on phones) and `menu__lang` (in the menu overlay, all sizes). Choice is saved in localStorage (`tdd-lang`) and applied on every page before the word light-up splits text.
- Form field `name`s and email subjects stay English so TDD gets consistent emails.
- **No em dashes (—) anywhere** - Daniël wants a plain hyphen (-) instead. En dashes in times (08:00 – 16:00) are fine.
- Browser-tab titles use no dashes at all: `Page | The Daily Drip`.

## Pages
All pages share the logo, the pill nav, the menu overlay (hamburger → full-screen dark list, big display type, no numbers), the footer and `main.js` (each animation block only runs when its section exists).
- `index.html` - home (below)
- `menu.html` - short hero (70vh, menu-bg.webp sandwiches), **sticky tabs** Drinks / Lunch / Bakes (active tab follows the section in view), then DRINKS / LUNCH (yellow) / BAKES (`.carte`: title on top; drinks & bakes in a 3-column grid (2 on phones), lunch as Sandwiches | Salads columns). Daniël found the earlier one-long-list layout unclear - keep it compact.
- `company-coffee.html` - generic office-coffee page (Kantoorkoffie): hero, intro ("interested? email us"), contact form
Forms (`.js-form`) send **directly** via **FormSubmit** (`formsubmit.co/ajax/info@thedailydrip.nl`, no account) - never via the visitor's mail app (Daniël doesn't want that). If the background request fails, the form does a classic POST to `formsubmit.co/info@thedailydrip.nl` (`action`), which redirects back via `_next` (`?sent=1` shows the thank-you). The first submission triggers an activation email to info@thedailydrip.nl that must be confirmed once. Only works on a real URL, not a file opened from disk. Honeypot field `_honey`.

Removed for now (Daniël will add them back once the info is clear): the coffee-beans ordering (steps, bean cards, order form) and the **coffee training** page (`training.html`). Their photos are still in `img/`.

## Structure (index.html)
1. **Hero** - 240vh, sticky; bg photo zooms slowly, portrait photo card rises through; corner texts.
2. **Mission** - white; text lights up word by word on scroll; 3 floating photos with `data-speed` parallax.
3. **Statement** - "More espresso, less depresso" over parallax bg; side text + See menu (→ menu.html).
4. **Footer** - visit / hours / contact + logo.

Removed on Daniël's request - don't bring back: the "A day at the Drip" stacked-photo section (felt like Tinder cards), the 4 menu cards on home (Flat white, Sandwich of the day…) the hero coordinates, the **Catering** section/menu item and **Visit** in the nav. He does like the card styling (`.cards` / `.card`) - reuse it on a future menu page.

## Images
Photos live in `img/` as **.webp** (~100–250 KB, max 2000px wide; full-bleed backgrounds also have a 1000px `-sm.webp` used via `srcset`). CSS/JS links carry `?v=N` - bump it when you change them so browsers/GitHub Pages don't serve a stale copy. Placeholders show the expected filename until the file exists. Filled so far from Daniël's first 5 photos (latte art top-down, storefront, interior seating, baguettes, bar counter) - mission-2/3 and the door images are crops of those and should be replaced by real photos:
- Home: all filled (hero-bg, hero-card, mission-1..3, statement-bg, door-company, door-training, catering, logo.svg)
- Company coffee: all filled (company-bg, beans-bg = beans close-up)
- Training: all filled (training-bg, course-*, training-1..3 = iced latte pour, latte + yellow lamp, can sealer)
- Spare: `drinks.webp` (TDD juice cans, was the menu hero) and `catering.webp` (man with sandwich tray, was the catering section) - for a future menu page.

## Open / to do
- [x] Logo in + fade-in check
- [x] Photos: every slot filled (22 TDD photos so far)
- [x] Menu as HTML page (from Daniël's Drinks / Lunch / Bakes menus; no prices shown)
- [x] Brand yellow from the brand file (`#ECB43F`)
- [x] Coolvetica font files in `fonts/`
- Copy uses TDD's real slogans where possible: "hustle, drip, sip" (cans/shirts) and "more espresso, less depresso" (mirror in the shop). Statement title is "More espresso, less depresso".
- [x] Sidebar menu overlay + LAP-style pill nav
- [x] NL/EN toggle
- Instagram: https://www.instagram.com/thedailydrip_zuidas/ (footer + menu overlay)
- All emails/forms go to info@thedailydrip.nl (confirmed by Daniël)
- [ ] Bring back beans ordering + coffee training once Daniël has the info
- [x] Forms → FormSubmit (confirm the activation email on info@ after the first test)
- [x] Hero card photo = barista in the TDD t-shirt ("hustle, drip and sip")
- [ ] Optional: statement background as looping video (like the reference's wave)
- [ ] SEO: LocalBusiness structured data, OG image
