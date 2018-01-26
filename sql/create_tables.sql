-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE Kayttaja (
  id SERIAL PRIMARY KEY,
  username varchar(120) NOT NULL,
  password varchar(120) NOT NULL
);

CREATE TABLE Tehtava (
  id SERIAL PRIMARY KEY,
  kayttaja_id INTEGER REFERENCES Kayttaja(id),
  kuvaus varchar(200) NOT NULL,
  prioriteetti int NOT NULL,
);

CREATE TABLE Luokka (
  id SERIAL PRIMARY KEY,
  kayttaja_id INTEGER REFERENCES Kayttaja(id),
  kuvaus varchar(200) NOT NULL,
);

CREATE TABLE TehtavaLuokka (
  tehtava_id INTEGER REFERENCES Tehtava(id),
  luokka_id INTEGER REFERENCES Luokka(id)
);
