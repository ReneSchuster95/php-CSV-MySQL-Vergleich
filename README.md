# PHP CSV-MySQL Vergleich

Dieses Projekt bietet eine einfache Webanwendung zum Vergleichen zweier CSV-Dateien im Format `ID;Bezeichnung` und zur anschließenden Übernahme von Änderungen in eine MySQL-Datenbank.

## Funktionen

- Upload von **alter** (Datenbankexport) und **neuer** (Katalogimport) CSV-Datei
- Automatischer Vergleich:
  - Neue Einträge (in neuer CSV, aber nicht in alter)
  - Geänderte Bezeichnungen (gleiche ID, andere Bezeichnung)
- Checkbox-Auswahl zum Speichern ausgewählter Änderungen
- Möglichkeit zum Download von Ergebnislisten als CSV
- Einfache Speicherung in MySQL-Datenbank (`Artikel`-Tabelle)

## CSV-Format

Die CSV-Dateien müssen folgendes Format haben:

ID;Bezeichnung
123;Produkt A
456;Produkt B


## Setup

1. **Datenbank einrichten**

Importiere `db.sql` in deine MySQL-Datenbank:

mysql -u [benutzer] -p [datenbankname] < db.sql

    Datenbankverbindung konfigurieren

Bearbeite die Datei db.php und passe die Zugangsdaten an:

$db = new mysqli("localhost", "user", "password", "datenbankname");

## Voraussetzungen

    PHP 7+

    MySQL

    Webserver (Apache, Nginx, XAMPP etc.)