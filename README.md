# GlinArt — Grnčarska prodavnica

GlinArt je sajt za grnčarsku radnju: proizvodi po kategorijama, korpa, porudžbina. Admin dodaje kategorije/proizvode (slike, cene); korisnik naručuje; admin prima mejl sa .xlsx tabelom porudžbine. Aplikacija je izgrađena u Laravel (PHP) frameworku.

## Uloge

- **Admin**: kategorije i proizvodi (CRUD, slike, cene), pregled porudžbina, promena statusa.
- **Korisnik**: pregled proizvoda, dodavanje u korpu, porudžbina (bez obavezne registracije). Nakon porudžbine admin prima mejl sa prilogom .xlsx (stavke + ukupno).

## Lokalizacija

Jezici: **en**, **sr**, **mk**, **bg**, **sq**. Izbor jezika u header-u (dropdown). Jezik se čuva u session; za promenu dodaj `?locale=sr` (ili drugi kod).

## Baza (MySQL)

Projekat koristi **MySQL** (lokalno i na produkciji).

**Lokalno:**

1. Kreiraj praznu bazu (npr. `glinart`):
   ```bash
   mysql -u root -e "CREATE DATABASE glinart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```
   Ili u phpMyAdmin / MySQL Workbench: New Database → ime `glinart`.

2. U `.env` podesi:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=glinart
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   (Lozinku stavi ako ti MySQL zahteva.)

3. Migracije i seed:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

**Produkcija (npr. Hostinger):**

- U hPanel-u već imaš MySQL bazu i korisnika. U `.env` na serveru stavi `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` koje ti dodele.
- Na serveru pokreni samo: `php artisan migrate` (seed po želji).

## Pokretanje

```bash
cp .env.example .env
php artisan key:generate
# Kreiraj MySQL bazu (vidi "Baza (MySQL)" iznad), pa:
php artisan migrate
php artisan db:seed
php artisan storage:link
npm install && npm run build
php artisan serve
```

- **Admin**: `misa.spale@gmail.com` / `password`
- **Korisnik**: `test@example.com` / `password`

## Mejl

Porudžbina šalje mejl na `MAIL_FROM_ADDRESS` (.env). Za produkciju podesi `MAIL_MAILER=smtp` i SMTP parametre; inače se loguje u `storage/logs/laravel.log`.

---

## Tehnologija

GlinArt koristi [Laravel](https://laravel.com) (PHP) framework.

## Licenca

Laravel framework je open-source softver pod [MIT licencom](https://opensource.org/licenses/MIT).
