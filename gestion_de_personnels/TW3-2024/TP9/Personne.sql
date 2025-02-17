DROP TABLE IF EXISTS `Personne`;
CREATE TABLE Personne (
  idP INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  age INT NOT NULL
);

INSERT INTO `Personne` (`idP`, `nom`, `age`) VALUES
(1, 'Devignes', 20),
(2, 'Chambeaux', 34),
(3, 'Bernard', 26),
(4, 'Lefevre', 18);
