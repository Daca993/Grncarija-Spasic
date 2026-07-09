# Domain arhitektura

Poslovna logika i entiteti su organizovani po domenima.

## Struktura

- **Catalog** – kategorije i proizvodi (Category, Product + prevodi)
- **Cart** – korpa (CartItem, CartService)
- **Order** – porudžbine (Order, OrderItem, OrderPlaced mail)
- **User** – korisnik (User model za auth)

## Pravila

- Kontroleri i Livewire koriste samo klase iz `App\Domain\*`.
- Migracije ostaju u `database/migrations/`; tabele su nepromenjene.
- Auth koristi `App\Domain\User\Models\User` (config/auth.php).
