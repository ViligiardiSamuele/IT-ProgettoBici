create table
  Utenti (
    id_utente int (11) AUTO_INCREMENT NOT NULL, -- pk
    nome varchar(20) NOT NULL,
    cognome varchar(20) NOT NULL,
    nascita char(10) NOT NULL,
    email varchar(40) NOT NULL UNIQUE,
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
    'Mario',
    'Super',
    '1985-09-13',
    'nintendo.official@gmail.com',
    SHA2 ('1800', 256)
  ),
  (
    'Fausto',
    'Bomboclat',
    '1952-10-07',
    'nonLoSo.srl@gmail.com',
    SHA2 ('4321', 256)
  );

insert into
  Gare (nome, minEta, maxConcorrenti)
values
  ("Gara Lunga", 50, 275),
  ("Gara Media", 10, 100),
  ("Gara Corta", -1, 20);

insert into
  Organizzatori (id_gara, id_utente)
values
  (1, 1),
  (2, 2);

insert into
  Concorrenti (id_gara, id_utente)
values
  (1, 2),
  (2, 2);