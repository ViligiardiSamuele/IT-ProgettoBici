CREATE TABLE Utenti (
  id int(11) AUTO_INCREMENT NOT NULL,
  email varchar(40) NOT NULL,
  psw varchar(64) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO Utenti (email, psw) VALUES
('1@gmail.com', SHA2('1234',256)),
('2@gmail.com', SHA2('4321',256));