-- Datenbank erstellen (falls noch nicht vorhanden)
CREATE DATABASE php_vergleich;

USE php_vergleich;

-- Tabelle Artikel erstellen
CREATE TABLE Artikel (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Bezeichnung VARCHAR(255) NOT NULL
);