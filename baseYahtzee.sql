
CREATE SCHEMA yahtzee;
USE yahtzee;

CREATE TABLE juegos(
  id INT,
  lanzamientos INT,
  dado1 INT,
  dado2 INT,
  dado3 INT,
  dado4 INT,
  dado5 INT,
  jugada1 INT,
  jugada2 INT,
  jugada3 INT,
  jugada4 INT,
  jugada5 INT,
  jugada6 INT,
  jugada7 INT,
  jugada8 INT,
  jugada9 INT,
  jugada10 INT,
  jugada11 INT,
  jugada12 INT,
  jugada13 INT,
  total INT,
  jugador VARCHAR(245)
);

-- INSERT INTO JUEGOS VALUES(0,0,0,0,0,0,0,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,0,"yo");
