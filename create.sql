CREATE TABLE roles (
    nom_role VARCHAR(50) PRIMARY KEY,
    description TEXT
);

CREATE TABLE permissions (
    nom_permission VARCHAR(100) PRIMARY KEY,
    description TEXT
);

CREATE TABLE role_permission (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_role VARCHAR(50) NOT NULL,
    nom_permission VARCHAR(100) NOT NULL,
    FOREIGN KEY (nom_role) REFERENCES roles(nom_role) ON DELETE CASCADE,
    FOREIGN KEY (nom_permission) REFERENCES permissions(nom_permission) ON DELETE CASCADE,
    UNIQUE (nom_role, nom_permission)
);

CREATE TABLE equipes (
    id_equipe INT PRIMARY KEY AUTO_INCREMENT,
    nom_equipe VARCHAR(100),
    description TEXT,
    date_creation DATE,

    id_chef INT DEFAULT NULL,
    FOREIGN KEY (id_chef) REFERENCES users(id_user) ON DELETE SET NULL
);

CREATE TABLE users (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    photo VARCHAR(255),
    grade VARCHAR(50),
    domaine_recherche TEXT,
    biographie TEXT,
    role VARCHAR(50),
    poste VARCHAR(100) DEFAULT NULL,
    statut ENUM('actif', 'suspendu', 'inactif') DEFAULT 'actif',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_equipe INT,
    FOREIGN KEY (role) REFERENCES roles(nom_role) ON DELETE SET NULL,
    FOREIGN KEY (id_equipe) REFERENCES equipes(id_equipe) ON DELETE SET NULL
);

CREATE TABLE projets (
    id_projet INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(200),
    description TEXT,
    thematique VARCHAR(100),
    type_financement VARCHAR(100),
    statut ENUM('en_cours', 'termine') DEFAULT 'en_cours',
    date_debut DATE,
    date_fin DATE,

    id_chef INT,
    FOREIGN KEY (id_chef) REFERENCES users(id_user) ON DELETE SET NULL
);

CREATE TABLE projet_user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_projet INT,
    id_user INT,
    FOREIGN KEY (id_projet) REFERENCES projets(id_projet) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE partenaires (
    id_partenaire INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(150),
    type ENUM('universitaire', 'industriel', 'organisme'),
    logo VARCHAR(255),
    site_web VARCHAR(255),
    description TEXT
);

CREATE TABLE projet_partenaire (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_projet INT NOT NULL,
    id_partenaire INT NOT NULL,
    FOREIGN KEY (id_projet) REFERENCES projets(id_projet) ON DELETE CASCADE,
    FOREIGN KEY (id_partenaire) REFERENCES partenaires(id_partenaire) ON DELETE CASCADE,
    UNIQUE (id_projet, id_partenaire)
);

CREATE TABLE publications (
    id_publication INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255),
    resume TEXT,
    type ENUM('article', 'these', 'rapport', 'communication', 'poster'),
    doi VARCHAR(100),
    lien_telechargement VARCHAR(255),
    annee INT,
    domaine VARCHAR(100),
    date_publication DATE,
    statut ENUM('en_attente', 'valide', 'rejete')
);

CREATE TABLE publication_auteur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT,
    id_publication INT,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_publication) REFERENCES publications(id_publication) ON DELETE CASCADE
);

CREATE TABLE projet_publication (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_projet INT NOT NULL,
    id_publication INT NOT NULL,
    FOREIGN KEY (id_projet) REFERENCES projets(id_projet) ON DELETE CASCADE,
    FOREIGN KEY (id_publication) REFERENCES publications(id_publication) ON DELETE CASCADE,
    UNIQUE (id_projet, id_publication)
);

CREATE TABLE equipements (
    id_equipement INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    type VARCHAR(50),
    description TEXT,
    statut ENUM('libre', 'maintenance'),
    localisation VARCHAR(100)
);

CREATE TABLE reservations (
    id_reservation INT PRIMARY KEY AUTO_INCREMENT,
    date_debut DATETIME,
    date_fin DATETIME,
    date_reservation DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_user INT,
    id_equipement INT,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_equipement) REFERENCES equipements(id_equipement)
);

CREATE TABLE actualites (
    id_actualite INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(200),
    description TEXT,
    type ENUM('projet', 'publication', 'evenement', 'soutenance'),
    date_publication DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
);

CREATE TABLE evenements (
    id_evenement INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(200),
    description TEXT,
    type VARCHAR(50),
    isPublic BOOLEAN NOT NULL DEFAULT FALSE,
    lieu VARCHAR(100),
    date_debut DATETIME,
    date_fin DATETIME,
    nb_max_participants INT
);

CREATE TABLE inscription_evenement (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_evenement INT,
    id_user INT NULL,
    date_inscription DATETIME,
    FOREIGN KEY (id_evenement) REFERENCES evenements(id_evenement),
    FOREIGN KEY (id_user) REFERENCES users(id_user)
);

CREATE TABLE opportunites (
    id_opportunite INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(200),
    type ENUM('stage', 'these', 'bourse', 'collaboration'),
    description TEXT,
    date_limite DATE,
    contact VARCHAR(255),
    statut ENUM('ouverte', 'fermee')
);

CREATE TABLE parametres (
    id_parametre INT PRIMARY KEY AUTO_INCREMENT,
    cle VARCHAR(50) UNIQUE,
    valeur TEXT,
    description VARCHAR(255)
);

CREATE TABLE contacts (
    id_contact INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    email VARCHAR(150),
    sujet VARCHAR(200),
    message TEXT,
    date_envoi DATETIME,
    statut ENUM('nouveau', 'lu', 'traite')
);
