# GlinArt – slike za glinart.com

Stavi ovde slike koje koristi sajt. CSS u projektu referiše ove putanje.

## Design pack (GlinArt Images and Design)

| Fajl | Gde se koristi |
|------|----------------|
| **hero.png** | Hero sekcija na početnoj (pun ekran, ruke na točku). |
| **about.png** | Sekcija „O nama” – slika levo u etno okviru. |
| **process-oblikovanje.png** | Kako nastaje – kartica „Oblikovanje”. |
| **process-pecenje.png** | Kako nastaje – kartica „Pečenje”. |
| **process-glazura.png** | Kako nastaje – kartica „Glaziranje”. |
| **products.jpg** | Istaknuti proizvodi – blaga pozadina (opciono). |

## Ostalo

| Fajl | Namena |
|------|--------|
| **etno-border.png** | Gornja/donja dekorativna traka (header, footer). |
| **paper-bg.png** | *(opciono)* Alternativna body pozadina (klasa `.body-paper-bg`). |
| **etno-kutak.jpg** / **radionica.jpg** | *(zastarelo)* Zamenjeno sa about.jpg i process.jpg. |

## Opciono (shop)

- **product-1.jpg**, **product-2.jpg** … – slike proizvoda (ili se koriste iz baze).

## Korišćenje u kodu

- Etno traka: `<div class="etno-border"></div>` ili postojeće `etno-border-top` / `etno-border-bottom`.
- Hero: sekcija sa klasom `hero` ili `hero-section` + `hero-bg` i `style="background-image: url('/assets/img/hero-pottery.jpg')"`.
- Pozadina: body klasa `body-paper-bg` (ako koristiš paper-bg.jpg) ili `body-texture` (bež + lagana SVG tekstura, preporučeno za brzinu).

Font: Cormorant Garamond. Boje: `--etno-red`, `--etno-brown`, `--etno-beige` u CSS.
