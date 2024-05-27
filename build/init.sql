create table
  Utenti (
    id_utente int (11) AUTO_INCREMENT NOT NULL, -- pk
    nome varchar(20) NOT NULL,
    cognome varchar(20) NOT NULL,
    nascita date NOT NULL,
    email varchar(40) NOT NULL,
    psw varchar(64) NOT NULL,
    PRIMARY KEY (id_utente)
  );

create table
  Gare (
    id_gara int auto_increment, -- pk
    nome varchar(40) not null,
    maxConcorrenti int default -1, -- (-1 = disattivato)
    minEta int default -1, -- (-1 = disattivato)
    chiusa boolean not null default false,
    primary key (id_gara)
  );

create table
  Organizzatori (
    id_gara int not null, -- pk
    id_utente int not null, -- pk
    foreign key (id_gara) references Gare (id_gara),
    foreign key (id_utente) references Utenti (id_utente),
    primary key (id_gara, id_utente)
  );

create table
  Concorrenti (
    id_gara int not null, -- pk
    id_utente int not null, -- pk
    foreign key (id_gara) references Gare (id_gara),
    foreign key (id_utente) references Utenti (id_utente),
    primary key (id_gara, id_utente)
  );

insert into
  Utenti (nome, cognome, nascita, email, psw)
values
  (
    'Tizio',
    'Brutto',
    '2020-01-01',
    '1@gmail.com',
    SHA2 ('1234', 256)
  ),
  (
    'Tizia',
    'Brutta',
    '1950-01-01',
    '2@gmail.com',
    SHA2 ('4321', 256)
  );

insert into
  Gare (nome, minEta, maxConcorrenti)
values
  ("Gara Lunghissima", 10, 275),
  ("Gara Media", -1, 25),
  ("Gara Corta", 50, 57);

insert into
  Organizzatori (id_gara, id_utente)
values
  (1, 1);

insert into
  Concorrenti (id_gara, id_utente)
values
  (1, 2);