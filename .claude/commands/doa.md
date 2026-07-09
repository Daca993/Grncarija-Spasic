# Vodič za Doменски-Оrijentisanu Arhitekturu (DOA) — TaxMate

Ovaj skill definiše kako se dodaje novi kod u TaxMate backend. Uvek sledi ova pravila.

## Struktura projekta

```
app/
├── Domain/                    # Sva poslovna logika
│   ├── User/                  # Auth, profil korisnika
│   ├── Business/              # Poslovni profil (PIB, MB, poreski režim)
│   ├── Dashboard/             # Agregacija podataka za dashboard
│   ├── Document/              # Dokumenti (računi, priznanice)
│   ├── Tax/                   # Poreske obaveze i kalkulacije
│   ├── Chat/                  # AI asistent
│   └── Report/                # Izveštaji i upozorenja (alerts)
├── Infrastructure/            # Integracije sa eksternim sistemima (budući)
└── Support/                   # Cross-domain helperi (formatiranje, itd.)
```

## Pravila za strukturu unutar domena

```
app/Domain/[Domen]/
├── Http/                      # Web sloj — SAMO za ceo domen
│   ├── Controllers/           # Single-action ili resource kontroleri
│   ├── Requests/              # FormRequest klase (validacija)
│   └── Resources/             # API Resource klase (transformacija)
├── [SubDomen]/                # Svaki subdomen je zasebna celina
│   ├── Models/                # Eloquent modeli
│   ├── Repositories/          # Svi DB upiti (Eloquent)
│   ├── Services/              # Poslovna logika
│   ├── Enums/                 # Enumi (SCREAMING_SNAKE_CASE case-ovi)
│   └── Data/                  # DTOs (Write DTOs i Read/View DTOs)
```

## Imenovanje

| Tip | Konvencija | Primer |
|-----|-----------|--------|
| Folder/Namespace | `PascalCase`, jedninа | `Tax/TaxObligation/` |
| Model | `PascalCase`, jedninа | `TaxObligation` |
| Repository | `[Model]Repository` | `TaxObligationRepository` |
| Interface | `[Klasa]Interface` | `TaxObligationRepositoryInterface` |
| Service | `[Akcija]Service` | `TaxCalculationService` |
| DTO (zapis) | `[Koncept]Data` | `CreateDocumentData` |
| DTO (čitanje) | `[Koncept]ViewData` | `DocumentViewData` |
| Enum | `PascalCase`, singular, bez Enum sufiksa | `TaxRegime`, `DocumentStatus` |
| Enum case-ovi | `SCREAMING_SNAKE_CASE` | `case STVARNI_TROSKOVI = 'stvarni_troskovi';` |
| DB tabela | `snake_case`, množina | `tax_obligations` |
| DB kolona | `snake_case`, jedninа | `due_date` |

## Kontroler — šta SME i šta NE SME

✅ Prima Request, validira kroz FormRequest, poziva JEDAN servis, vraća odgovor
❌ NE sadrži poslovnu logiku (if/else na osnovu poslovnih pravila)
❌ NE komunicira direktno sa modelima (Bill::create())
❌ NE komunicira direktno sa repozitorijumom

```php
// Primer Single Action Controllera
class ConfirmDocumentController extends Controller
{
    public function __construct(private readonly DocumentService $service) {}

    public function __invoke(Request $request, Document $document): JsonResponse
    {
        $confirmed = $this->service->confirm($document, $request->user());
        return $this->success(['document' => DocumentViewData::from($confirmed)]);
    }
}
```

## Response format

Svi odgovori koriste `success()` i `error()` metode iz base `Controller`:

```php
// Uspeh
return $this->success($data);               // { success: true, data: ... }
return $this->success($data, 'Poruka', 201); // sa statusom i porukom

// Greška
return $this->error('Nije pronađeno.', 404); // { success: false, error: "...", message: "..." }
```

## Komunikacija između domena

**Na granici domena (obavezno) → koristi Interface:**
```php
// Tag domain zavisi od Finance domain-a
use App\Domain\Finance\Bill\Contracts\BillRepositoryInterface;
```

**Unutar istog domena (opcionalno) → direktna klasa je OK:**
```php
// BillingService (unutar Finance) koristi BillRepository direktno
use App\Domain\Finance\Bill\Repositories\BillRepository;
```

**Asinhrona komunikacija → Events:**
```php
// User domain ispaljuje, Finance domain sluša
event(new CustomerRegistered($customer->id));
```

## Enumi

Koristiti PHP native `enum` sa `string` backed tipom. Validacija u Request klasama:

```php
use Illuminate\Validation\Rules\Enum;

'tax_regime' => ['required', new Enum(TaxRegime::class)],
```

Enum cast u modelu:
```php
protected function casts(): array
{
    return ['tax_regime' => TaxRegime::class];
}
```

## Rute

Svaki domen ima sopstveni route fajl u `routes/api/[domen].php`:

```
routes/api/
├── auth.php
├── business.php
├── dashboard.php
├── documents.php
├── taxes.php
├── chat.php
├── reports.php
└── alerts.php
```

`routes/api.php` samo uključuje ove fajlove sa odgovarajućim prefixom i middleware-om.

## Migracije

Format: `YYYY_MM_DD_HHMMSS_create_[tabela]_table.php`
Redosled: kreiraj zavisne tabele pre tabela sa FK.

## Seederi

Statički podaci (kategorije, sugestije) idu u posebne seedere pozivane iz `DatabaseSeeder`.

## Trenutni domeni i API endpoint

| Domen | Rute |
|-------|------|
| User | `POST /auth/login`, `GET /auth/me`, `POST /auth/logout` |
| Business | `GET/PUT /business/profile` |
| Dashboard | `GET /dashboard` |
| Document | `GET/POST /documents`, `GET /documents/{id}`, `POST /documents/{id}/confirm`, `GET /documents/categories` |
| Tax | `GET /taxes/obligations`, `GET /taxes/prediction`, `POST /taxes/simulate` |
| Chat | `GET /chat/suggestions`, `GET/POST /chat/messages` |
| Report | `GET /reports`, `GET /alerts` |

## Kada dodaješ novi endpoint

1. Kreiraj migration (ako treba nova tabela)
2. Kreiraj Model u `[Domen]/[SubDomen]/Models/`
3. Kreiraj Repository u `[Domen]/[SubDomen]/Repositories/`
4. Kreiraj Service u `[Domen]/[SubDomen]/Services/` (poslovna logika)
5. Kreiraj Controller u `[Domen]/Http/Controllers/`
6. Dodaj rutu u `routes/api/[domen].php`
