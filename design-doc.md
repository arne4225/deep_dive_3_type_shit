# Design Document – Foodblog CMS

## Tech Stack
- PHP 8
- MySQL (PDO)
- HTML / CSS
- Geen frameworks

## Architectuur
- Server-side rendered PHP applicatie
- MVC-achtig:
  - index.php als entry point
  - database.php voor DB connectie
  - losse PHP bestanden per actie

## Belangrijke keuzes
- Geen authenticatie om scope klein te houden
- PDO voor veilige database queries
- Simpele structuur i.p.v. framework

## Bekende risico’s
- SQL fouten bij CRUD operaties
- Tijdgebrek bij extra features
- Structuur kan rommelig worden zonder discipline
