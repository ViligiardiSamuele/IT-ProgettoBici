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
    FOREIGN KEY (id_gara) REFERENCES Gare(id_gara) ON DELETE CASCADE,
    FOREIGN KEY (id_utente) REFERENCES Utenti(id_utente) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COMMENT = 'Tabella degli organizzatori delle gare';

CREATE TABLE
  Concorrenti (
    id_gara INT NOT NULL, -- pk
    id_utente INT NOT NULL, -- pk
    tempoInGara INT DEFAULT 0,
    PRIMARY KEY (id_gara, id_utente),
    FOREIGN KEY (id_gara) REFERENCES Gare(id_gara) ON DELETE CASCADE,
    FOREIGN KEY (id_utente) REFERENCES Utenti(id_utente) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COMMENT = 'Tabella dei concorrenti nelle gare';
