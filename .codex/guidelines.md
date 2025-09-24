# Project Guidelines (Laravel + Filament v4)

- Use Laravel 12 conventions and PSR-12 code style.
- Place Filament Resources in `app/Filament/Resources`.
- Prefer actions over custom controller endpoints where possible.
- Eager load relationships in tables to avoid N+1.
- Validation via Form components; policies in `app/Policies`.
- Run: `php artisan test` before PRs.