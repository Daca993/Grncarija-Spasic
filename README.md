# Primena novog dizajna na GlinArt / Grncarija-Spasic

Ovaj folder je za tvoj Laravel projekat (PhpStorm), ne za ovaj DC alat.
Sadrži samo dva fajla za zamenu.

## Šta radiš

1. **Zameni** sadržaj `tailwind.config.js` (root projekta) sadržajem `handoff/tailwind.config.js`.
2. **Zameni** sadržaj `resources/css/app.css` sadržajem `handoff/app.css`.
3. U `resources/views/layouts/shop.blade.php`, u `<head>`, zameni Google Fonts link:

   Staro:
   ```
   family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700
   ```
   Novo:
   ```
   family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Work+Sans:wght@400;500;600&family=Caveat:wght@600
   ```

4. Ornament traka: `<div class="etno-border-top"></div>` već postoji u `shop.blade.php` — sada koristi novu klasu iz `app.css` (cik-cak umesto pune linije). Opciono dodaj i `<div class="etno-border-bottom"></div>` odmah iznad `<footer>`.

5. Eyebrow tekst (rukom pisan akcent) — dodaj gde želiš iznad naslova, npr. u `home.blade.php` pre `<h1>` u hero-u:
   ```
   <span class="etno-eyebrow">ručno rađeno, sa ljubavlju</span>
   ```

## Šta OSTAJE isto

Sve klase (`.hero`, `.product-card`, `.about-grid`, `.process-steps`, `.section-dark`, itd.) i cela Blade/Livewire struktura — menjaju se samo boje i fontovi kroz CSS varijable i tailwind config, pa nema rizika da nešto pukne.

## Napomena o boji

`--color-primary` sad koristi `oklch()` direktno (podržano u svim modernim browserima). Ako ti treba hex fallback za stariji alat, javi pa konvertujem.
