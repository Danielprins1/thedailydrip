# The Daily Drip - WordPress-thema

Het ontwerp van deze repo als WordPress-thema. Bouwen: `./build.sh` maakt `dist/thedailydrip.zip`
(styles.css, main.js, fonts en de gebruikte foto's komen uit de hoofdmap, de PHP-templates uit `src/`).
Pas je het ontwerp aan in de hoofdmap, dan bouw je het thema opnieuw en upload je de nieuwe zip.

## Installeren

1. **Maak eerst een back-up** van de huidige WordPress-site (bijv. met UpdraftPlus of via de hosting).
2. WordPress-admin > **Weergave > Thema's > Nieuw thema > Thema uploaden** > kies `thedailydrip.zip` > **Nu installeren** > **Activeren**.
   Te groot voor de uploader? Pak de zip uit en zet de map `thedailydrip` via FTP / bestandsbeheer in `wp-content/themes/`.
3. Bij het activeren gebeurt automatisch:
   - Er komen pagina's **Menu**, **Corporate coffee** en **Corporate lunch** (een bestaande pagina met slug `menu` / `company-coffee` / `corporate-lunch` wordt hergebruikt; de oude inhoud blijft bewaard maar wordt niet getoond).
   - De **Menukaart** wordt gevuld met het huidige menu.
   - De homepage gebruikt automatisch het nieuwe ontwerp, wat er ook bij Instellingen > Lezen staat.
4. **Instellingen > Permalinks**: kies "Berichtnaam" en klik op Opslaan (zorgt voor nette adressen als /menu/).
5. **Adobe Fonts**: zet het domein (thedailydrip.nl) in je Adobe-webproject, anders laadt de payoff-letter niet.
6. Gebruik je een cachingplugin (WP Rocket, LiteSpeed…)? Leeg de cache.
7. Test het formulier op Corporate coffee en Corporate lunch één keer.

Elementor mag geïnstalleerd blijven; deze pagina's gebruiken het niet. Terug naar het oude thema kan altijd via Weergave > Thema's.

## Wat de klant zelf kan aanpassen

- **Teksten en foto's**: Weergave > **Customizer** > paneel **The Daily Drip**
  (Algemeen: adres, openingstijden, e-mail, Instagram, slogan · Home · Menu · Corporate coffee · Corporate lunch).
  Elke tekst heeft een Engels en een Nederlands veld ("(NL)"). Een enter in een veld = een nieuwe regel op de site.
  Foto's leeg laten = de standaardfoto uit het thema.
- **Menukaart**: in de zijbalk **Menu card**. Per item: naam, sectie (Drinks / Lunch - Sandwiches / Lunch - Salads / Bakes),
  omschrijving (voor broodjes en salades) en volgorde ("Order", lager = hoger op de kaart).
  Tekst tussen haakjes aan het eind van een naam wordt kleiner getoond, zoals "(grillworst)".

## Formulier

De formulieren op Corporate coffee en Corporate lunch worden door WordPress zelf gemaild naar het adres bij
Customizer > The Daily Drip > General > "Form answers are sent to" (standaard info@thedailydrip.nl).
Geen FormSubmit meer nodig. Komen de mails niet aan of in de spam? Installeer **WP Mail SMTP** en laat WordPress
via de info@-mailbox versturen. Spambescherming: verborgen honeypot-veld, minimale invultijd en max. 5 berichten per 10 minuten per bezoeker.

Bij een nieuwe themaversie (zip opnieuw uploaden) maakt WordPress ontbrekende pagina's, zoals Corporate lunch, vanzelf aan zodra je in de admin komt.

De bezoeker krijgt direct een bedankmail (huisstijl, NL of EN naar de gekozen taal) van no-reply@thedailydrip.nl met een kopie van het ingevulde bericht; antwoorden daarop gaan naar het info@-adres uit de Customizer. Komt die mail niet aan: WP Mail SMTP.
