CREATE DATABASE IF NOT EXISTS arrasgo;
USE arrasgo;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS chapitres;
DROP TABLE IF EXISTS indices;
DROP TABLE IF EXISTS etapes;
DROP TABLE IF EXISTS parcours;
DROP TABLE IF EXISTS users_admins;
DROP TABLE IF EXISTS personnages;
CREATE TABLE users_admins (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nom_user VARCHAR(100),
    email_user VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe_user VARCHAR(255) NOT NULL,
    role_user VARCHAR(50) NOT NULL DEFAULT 'admin',
    date_creation_user TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO users_admins (nom_user, email_user, mot_de_passe_user)
VALUES ('Admin', 'admin@exemple.com', '$2y$12$6mL8LYyKct1YZEAWmnsF5ew56CVcGGYpd.9bb67vWa7LO9yUopkXe');
CREATE TABLE parcours (
    id_parcours INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    nom_parcours VARCHAR(100) NOT NULL,
    description_parcours TEXT,
    image_parcours VARCHAR(255),
    statut TINYINT(1) DEFAULT 0, -- 0 = brouillon, 1 = en ligne
    mode_geo_parcours BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (id_user) REFERENCES users_admins(id_user) ON DELETE CASCADE
);
CREATE TABLE etapes (
    id_etape INT AUTO_INCREMENT PRIMARY KEY,
    id_parcours INT NOT NULL,
    titre_etape VARCHAR(255) NOT NULL,
    mp3_etape VARCHAR(255),
    image_header VARCHAR(255),
    image_question VARCHAR(255),
    indice_etape_texte TEXT,
    indice_etape_image VARCHAR(255),
    question_etape TEXT,
    reponse_attendue VARCHAR(255),
    latitude DECIMAL(9,6),
    longitude DECIMAL(9,6),
    ordre_etape INT,
    type_validation ENUM('reponse', 'reponse+geo') NOT NULL DEFAULT 'reponse',
    FOREIGN KEY (id_parcours) REFERENCES parcours(id_parcours) ON DELETE CASCADE
);
CREATE TABLE chapitres (
    id_chapitre INT AUTO_INCREMENT PRIMARY KEY,
    id_etape INT NOT NULL,
    titre_chapitre VARCHAR(255) DEFAULT NULL,
    texte_chapitre TEXT,
    ordre_chapitre INT,
    image_chapitre VARCHAR(255), -- AJOUTE CETTE LIGNE
    FOREIGN KEY (id_etape) REFERENCES etapes(id_etape) ON DELETE CASCADE
);
CREATE TABLE personnages (
    id_personnage INT AUTO_INCREMENT PRIMARY KEY,
    nom_personnage VARCHAR(255) NOT NULL,
    image_personnage VARCHAR(255),
    mp3_personnage VARCHAR(255), -- fichier audio de retranscription
    description_personnage TEXT
);
CREATE TABLE sessions (
    id_session CHAR(36) PRIMARY KEY, -- UUID (ex: généré en JS ou PHP)
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    derniere_etape_validee INT NULL,
    FOREIGN KEY (derniere_etape_validee) REFERENCES etapes(id_etape) ON DELETE SET NULL ON UPDATE CASCADE
);
CREATE TABLE parcours_personnages (
    id_parcours INT,
    id_personnage INT,
    PRIMARY KEY (id_parcours, id_personnage),
    FOREIGN KEY (id_parcours) REFERENCES parcours(id_parcours) ON DELETE CASCADE,
    FOREIGN KEY (id_personnage) REFERENCES personnages(id_personnage) ON DELETE CASCADE
);