# Diagrammen – Foodblog CMS

## Applicatie-overzicht
```mermaid
flowchart TD
    User --> Browser
    Browser --> PHP_App
    PHP_App --> Database
    Database --> PHP_App
    PHP_App --> Browser

sequenceDiagram
    User->>Browser: Formulier invullen
    Browser->>PHP_App: POST /create
    PHP_App->>Database: INSERT post
    Database-->>PHP_App: OK
    PHP_App-->>Browser: Redirect naar overzicht

## Database-relaties
```mermaid
erDiagram
    USERS ||--o{ POSTS : writes
    POSTS ||--o{ POST_TAGS : has
    TAGS ||--o{ POST_TAGS : contains
