CREATE TABLE utilisateurs(
   id_utilisateur SERIAL,
   username VARCHAR(50) NOT NULL,
   email VARCHAR(255) NOT NULL,
   hash VARCHAR(255) NOT NULL,
   role VARCHAR(20) NOT NULL DEFAULT 'utilisateur',
   est_actif BOOLEAN NOT NULL DEFAULT TRUE,
   date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY(id_utilisateur),
   UNIQUE(username),
   UNIQUE(email)
);

CREATE TABLE types_oeuvre(
   id_type_oeuvre SERIAL,
   nom_type VARCHAR(100) NOT NULL,
   description_type TEXT,
   PRIMARY KEY(id_type_oeuvre),
   UNIQUE(nom_type)
);

CREATE TABLE oeuvres(
   id_oeuvre SERIAL,
   titre VARCHAR(200) NOT NULL,
   description TEXT NOT NULL,
   artiste VARCHAR(150) NOT NULL,
   annee_creation INT,
   dimensions VARCHAR(50),
   prix DECIMAL(15,2),
   image_url VARCHAR(255),
   date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   statut_oeuvre VARCHAR(50) NOT NULL DEFAULT 'disponible',
   id_type_oeuvre INT NOT NULL,
   id_utilisateur INT NOT NULL,
   PRIMARY KEY(id_oeuvre),
   FOREIGN KEY(id_type_oeuvre) REFERENCES types_oeuvre(id_type_oeuvre) ON DELETE RESTRICT ON UPDATE CASCADE,
   FOREIGN KEY(id_utilisateur) REFERENCES utilisateurs(id_utilisateur) ON DELETE SET NULL ON UPDATE CASCADE 
);