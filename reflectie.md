# Reflectie Dag 3

## Wat ga ik morgen verder bouwen?
- Post bewerken
- Post verwijderen
- Code opschonen

## Waar liep ik tegenaan?
- Database queries goed opzetten
- Structuur overzichtelijk houden

## Waar hielp AI het meest?
- User stories schrijven
- Diagrammen maken
- Structuur bepalen

# Reflectie dag 4 
- Vandaag heb ik een signup flow gebouwd met veilige wachtwoord hashing en database validatie.
- Vandaag heb ik een veilige login- en logout-flow gebouwd met PHP sessions en access control.
- Vandaag heb ik het aanmaken van posts geïmplementeerd.
Alleen ingelogde gebruikers kunnen posts maken en posts worden correct
gekoppeld aan de auteur via user_id.
- Vandaag heb ik tags toegevoegd aan posts via een many-to-many relatie.
- Posts kunnen meerdere tags hebben en bestaande tags worden hergebruikt.
- Vandaag heb ik de indexpagina gebouwd die posts toont met auteur en tags
via één efficiënte databasequery.
- Vandaag heb ik het bewerken van posts geïmplementeerd inclusief tags.
Alleen de eigenaar kan bewerken, en tags worden correct aangepast.
- Vandaag heb ik post-verwijderen toegevoegd met bevestiging. Alleen de eigenaar kan verwijderen en alle gekoppelde tags worden automatisch verwijderd.
- Vandaag heb ik een geavanceerde zoek- en filterfunctie toegevoegd.
Posts kunnen nu worden gezocht op titel, inhoud en tags, of gefilterd op een specifieke tag.
- Vandaag heb ik de show.php pagina gemaakt voor individuele posts.
Inclusief auteur, datum, content, tags en conditional edit/delete links.


## Reflectie op AI-gebruik – Deep Dive

### Welke AI tool gebruikte ik het meest?

Tijdens deze Deep Dive gebruikte ik vooral **ChatGPT**. Ik gebruikte het als hulp bij het begrijpen van opdrachten, het schrijven en verbeteren van PHP- en SQL-code en het opsporen van fouten.

### Waar maakte AI mij sneller?

AI maakte mij vooral sneller bij:

* het begrijpen van nieuwe concepten
* het debuggen van foutmeldingen
* het schrijven van basisstructuren voor code (zoals CRUD, SQL-queries)

In plaats van lang zoeken op Google kreeg ik snel een richting of voorbeeld waar ik mee verder kon.

### Waar ging AI de mist in?

Soms gaf AI code die:

* niet helemaal aansloot op mijn database-structuur
* kleine fouten bevatte (bijv. verkeerde veldnamen)
* te snel aannames deed over wat al bestond in mijn project

Hierdoor werkte iets soms niet direct, ook al leek de oplossing logisch.

### Wanneer moest ik zelf ingrijpen?

Ik moest zelf ingrijpen wanneer:

* code errors gaf die ik echt zelf moest uitpluizen
* ik moest aanpassen aan mijn eigen projectstructuur
* ik wilde begrijpen *waarom* iets werkte, in plaats van het blind over te nemen

Dit dwong mij om alsnog goed na te denken en de code te lezen.

### Zou je AI op dezelfde manier opnieuw gebruiken?

Ja, maar wel bewuster. Ik zou AI blijven gebruiken als **hulpmiddel**, niet als vervanging van mijn eigen denkwerk.

---

## Conclusie

### Voordelen van AI voor developers

* Sneller leren en werken
* Hulp bij debuggen
* Goede uitleg bij nieuwe onderwerpen
* Minder vastlopen bij kleine problemen

### Risico’s van AI

* Te afhankelijk worden van gegenereerde oplossingen
* Code gebruiken zonder het te begrijpen
* Fouten overnemen die niet direct zichtbaar zijn

### Eindreflectie

AI is voor mij een sterke assistent geweest tijdens deze Deep Dive, maar alleen effectief wanneer ik zelf kritisch bleef. De combinatie van AI **én eigen nadenken** werkt het best.
