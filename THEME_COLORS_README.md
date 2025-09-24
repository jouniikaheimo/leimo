# Filament Theme Color Customization Tool

## Översikt

Detta verktyg låter dig anpassa ditt Filament-programs färgtema genom både webgränssnittet och kommandoraden. Du kan ändra huvudfärgen och 5 andra färger (sekundär, accent, framgång, varning, fara).

## Funktioner

### 1. Webgränssnitt (Settings-sida)
- **Lokalisering**: `/admin/settings` (kräver adminbehörighet)
- **Live förhandsvisning**: Se färgändringar direkt när du väljer dem
- **Komponentexempel**: Se hur färgerna ser ut på knappar och badges
- **Färgpaletter**: Automatisk generering av fullständiga färgpaletter från din basfärg
- **Säkra åtgärder**: Bekräftelse innan återställning till standardvärden

### 2. Kommandoradsgränssnitt (Artisan Command)

#### Lista alla färger
```bash
php artisan theme:colors list
```

#### Ändra en specifik färg
```bash
# Interaktivt (kommer att fråga dig)
php artisan theme:colors set

# Direkt med flaggor
php artisan theme:colors set --color=primary --value=#ff5722
```

#### Hämta en specifik färg
```bash
php artisan theme:colors get --color=primary
```

#### Återställ alla färger till standardvärden
```bash
php artisan theme:colors reset
```

## Tillgängliga färgtyper

1. **Primary** (`#3b82f6`) - Huvudfärg för knappar, länkar, markeringar
2. **Secondary** (`#6366f1`) - Sekundär färg för element
3. **Accent** (`#8b5cf6`) - Accentfärg för speciella element
4. **Success** (`#10b981`) - Färg för framgångsrika åtgärder
5. **Warning** (`#f59e0b`) - Färg för varningar och meddelanden
6. **Danger** (`#ef4444`) - Färg för fellägen och farliga åtgärder

## Teknisk implementation

### Filer som skapats/modifierades:

1. **`app/Filament/Pages/Settings.php`** - Huvudinställningssida med färgväljare
2. **`app/Services/ThemeColorService.php`** - Tjänst för färghantering
3. **`app/Providers/ThemeServiceProvider.php`** - Leverantör som applicerar färger
4. **`app/Console/Commands/ThemeColorsCommand.php`** - Artisan-kommando
5. **`resources/views/filament/pages/settings.blade.php`** - Blade-mall för inställningar
6. **`database/migrations/XXXX_add_theme_color_settings.php`** - Migration för standardfärger

### Så fungerar det:

1. **Färglagring**: Färger lagras i databasen via `settings`-tabellen med nycklar som `theme.primary_color`
2. **Paletgenerering**: Varje hex-färg konverteras till en fullständig palett (50-950 nyanser) automatiskt
3. **Live-applikation**: Färger appliceras via `FilamentColor::register()` i ThemeServiceProvider
4. **Persistence**: Färger bevaras mellan sessioner och appliceras vid varje sidladdning

## Användningsexempel

### Ändra till ett mörkt tema
```bash
php artisan theme:colors set --color=primary --value=#1f2937
php artisan theme:colors set --color=secondary --value=#374151
php artisan theme:colors set --color=accent --value=#6b7280
```

### Ändra till ett vårigt tema
```bash
php artisan theme:colors set --color=primary --value=#10b981
php artisan theme:colors set --color=secondary --value=#34d399
php artisan theme:colors set --color=accent --value=#6ee7b7
```

### Företagsfärger
```bash
php artisan theme:colors set --color=primary --value=#0066cc
php artisan theme:colors set --color=secondary --value=#004499
```

## Behörigheter

Settings-sidan kräver adminbehörighet som definieras av:
- `config('timeclock.dev_bypass_admin')` (utvecklingsläge)
- `config('timeclock.admin_emails')` (lista över admin-e-post)

## Säkerhetskopiering och återställning

Standardfärgerna är:
- Primary: `#3b82f6` (Blå)
- Secondary: `#6366f1` (Indigo) 
- Accent: `#8b5cf6` (Violett)
- Success: `#10b981` (Smaragd)
- Warning: `#f59e0b` (Bärnsten)
- Danger: `#ef4444` (Röd)

Du kan alltid återställa till dessa med reset-kommandot eller reset-knappen i webgränssnittet.

## Felsökning

Om färgerna inte appliceras korrekt:
1. Kontrollera att `ThemeServiceProvider` är registrerad i `bootstrap/providers.php`
2. Verifiera att migrationen har körts: `php artisan migrate:status`
3. Rensa cache: `php artisan cache:clear`
4. Kontrollera loggar för fel: `php artisan theme:colors list`

## Utvidgning

För att lägga till fler färger:
1. Lägg till nya fält i Settings-formuläret
2. Uppdatera `ThemeColorService::getAllColors()`
3. Lägg till standardvärden i migrationen
4. Uppdatera Artisan-kommandot med nya färgtyper
