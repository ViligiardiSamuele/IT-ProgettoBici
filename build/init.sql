CREATE TABLE
  Utenti (
    id_utente INT AUTO_INCREMENT NOT NULL, -- pk
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL,
    nascita DATE NOT NULL,
    email VARCHAR(40) NOT NULL UNIQUE,
    psw CHAR(64) NOT NULL,
    PRIMARY KEY (id_utente),
    INDEX (email)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COMMENT = 'Tabella degli utenti';

CREATE TABLE
  Gare (
    id_gara INT AUTO_INCREMENT NOT NULL, -- pk
    nome VARCHAR(40) NOT NULL,
    maxConcorrenti INT DEFAULT -1, -- (-1 = disattivato)
    minEta INT DEFAULT -1, -- (-1 = disattivato)
    chiusa BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (id_gara),
    INDEX (nome)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COMMENT = 'Tabella delle gare';

CREATE TABLE
  Organizzatori (
    id_gara INT NOT NULL, -- pk
    id_utente INT NOT NULL, -- pk
    PRIMARY KEY (id_gara, id_utente),
    FOREIGN KEY (id_gara) REFERENCES Gare (id_gara) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_utente) REFERENCES Utenti (id_utente) ON DELETE CASCADE ON UPDATE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COMMENT = 'Tabella degli organizzatori delle gare';

CREATE TABLE
  Concorrenti (
    id_gara INT NOT NULL, -- pk
    id_utente INT NOT NULL, -- pk
    tempoInGara INT DEFAULT 0,
    PRIMARY KEY (id_gara, id_utente),
    FOREIGN KEY (id_gara) REFERENCES Gare (id_gara) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_utente) REFERENCES Utenti (id_utente) ON DELETE CASCADE ON UPDATE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COMMENT = 'Tabella dei concorrenti nelle gare';

-- Inserimento di dati nella tabella Utenti
INSERT INTO
  Utenti (nome, cognome, nascita, email, psw)
VALUES
  (
    'Mario',
    'Rossi',
    '1985-01-01',
    'mario.rossi@example.com',
    SHA2 ('password1', 256)
  ),
  (
    'Luigi',
    'Verdi',
    '1990-05-15',
    'luigi.verdi@example.com',
    SHA2 ('password2', 256)
  ),
  (
    'Anna',
    'Bianchi',
    '1975-09-10',
    'anna.bianchi@example.com',
    SHA2 ('password3', 256)
  ),
  (
    'Giulia',
    'Neri',
    '2000-12-20',
    'giulia.neri@example.com',
    SHA2 ('password4', 256)
  );

-- Inserimento di dati nella tabella Gare
INSERT INTO
  Gare (nome, maxConcorrenti, minEta, chiusa)
VALUES
  ('Gara Lunga', 100, 50, FALSE),
  ('Gara Media', -1, 16, TRUE),
  ('Gara Corta', 10, -1, FALSE),

-- Inserimento di dati nella tabella Organizzatori
INSERT INTO
  Organizzatori (id_gara, id_utente)
VALUES
  (1, 1),
  (1, 2),
  (2, 3),
  (3, 4);

-- Inserimento di dati nella tabella Concorrenti
INSERT INTO
  Concorrenti (id_gara, id_utente, tempoInGara)
VALUES
  (1, 3, 3600),
  (1, 4, 3700),
  (2, 1, 1800),
  (2, 2, 1900),
  (3, 1, 1200),
  (3, 2, 1300),
  (4, 3, 2400),
  (4, 4, 2500);