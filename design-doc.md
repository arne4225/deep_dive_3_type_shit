# Design Document – Foodblog CMS

## Tech Stack
- PHP 8
- MySQL (PDO)
- HTML / CSS
- PHP sessions voor login status
- password_hash / password_verify


## Architectuur
- Server-side rendered PHP applicatie
- MVC-achtig:
  - index.php als entry point
  - database.php voor DB connectie
  - losse PHP bestanden per actie
  - Authenticatie via PHP sessions
- Relationele database met users, posts en tags
- Many-to-many relatie tussen posts en tags
- Login status wordt beheerd via PHP sessions
- Toegang tot protected routes wordt afgedwongen via middleware-achtige helper
- Tags worden beheerd via een many-to-many relatie (posts ↔ tags)
- Koppeling gebeurt via een junction table (post_tags)
- Overzichtspagina gebruikt één SQL-query met joins om posts, auteurs en tags op te halen





## Belangrijke keuzes
- Geen authenticatie om scope klein te houden
- PDO voor veilige database queries
- Simpele structuur i.p.v. framework

## Bekende risico’s
- SQL fouten bij CRUD operaties
- Tijdgebrek bij extra features
- Structuur kan rommelig worden zonder discipline
- Fouten in many-to-many relaties
- Onjuiste sessie-afhandeling
- Security fouten bij login
