CREATE TABLE IF NOT EXISTS #__vwkmatch
(
   id INT NOT NULL AUTO_INCREMENT,
   name TEXT NOT NULL,
   publish BOOL DEFAULT 0,
   ordering INT DEFAULT 0,
   PRIMARY KEY (id)
);
INSERT INTO #__vwkmatch (id, name) VALUES (1, 'Koenigschiessen');


CREATE TABLE IF NOT EXISTS #__vwkresult
(
   id INT NOT NULL AUTO_INCREMENT,
   matchid INT NOT NULL,
   name TEXT NOT NULL,
   xml TEXT,
   updatetime DATETIME,
   publish BOOL DEFAULT 0,
   ordering INT DEFAULT 0,
   PRIMARY KEY (id)
);
INSERT INTO #__vwkresult (id, matchid, name) VALUES (1, 1, 'Ringwertung SK');
