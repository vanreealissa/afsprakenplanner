# Afsprakenplanner

Een online afsprakensysteem voor een (fictieve) kapsalon, gebouwd met Laravel.
Klanten kiezen een behandeling, een dag en een vrij tijdstip. De salon ziet alle
afspraken per dag in een eenvoudig beheeroverzicht.

## Functies

- Overzicht van behandelingen met duur en prijs
- Beschikbare tijden per dag, berekend uit openingstijden en bestaande afspraken
  (afspraken kunnen nooit overlappen, ook niet als twee klanten tegelijk boeken)
- Afspraak boeken met validatie en Nederlandse foutmeldingen
- Bevestigingspagina via een ondertekende URL, zodat niemand andermans afspraak kan inzien
- Beheer: agenda per dag, verwachte omzet en afspraken annuleren

## Wat laat dit project zien?

| Onderdeel | Waar |
| --- | --- |
| Eloquent-relaties en query scopes | `app/Models` |
| Businesslogica in een aparte service | `app/Services/AvailabilityService.php` |
| Form Requests voor validatie | `app/Http/Requests/StoreAppointmentRequest.php` |
| Signed routes | `routes/web.php`, `BookingController` |
| Factories en seeders | `database/` |
| Feature tests met een vaste "nu"-tijd | `tests/Feature/BookingTest.php` |

## Installeren

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Gebruik je Laravel Herd? Zet de map in `~/Herd` en open http://afsprakenplanner.test.

## Testen

```bash
php artisan test
```

## Let op

Het beheer (`/beheer`) is in deze demo niet afgeschermd. Voor echt gebruik voeg je
inloggen toe, bijvoorbeeld met Laravel Breeze, en zet je de beheerroutes achter de
`auth`-middleware.
