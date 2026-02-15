-- File di definizione dello schema del database.
-- Contiene la creazione delle tabelle, chiavi primarie,
-- chiavi esterne e vincoli di integrità.
-- =====================================================
-- DATABASE
-- =====================================================

CREATE DATABASE IF NOT EXISTS gestionale_studio;
USE gestionale_studio;

-- =====================================================
-- USERS
-- =====================================================

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- CLIENTI
-- =====================================================

CREATE TABLE clienti (
    cliente_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100) NOT NULL,
    data_nascita DATE,
    professione VARCHAR(150),
    telefono VARCHAR(50),
    email VARCHAR(150),
    indirizzo VARCHAR(255)
);

-- =====================================================
-- APPUNTAMENTI
-- =====================================================

CREATE TABLE appuntamenti (
    appuntamento_id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    data DATE NOT NULL,
    ora_inizio TIME NOT NULL,
    ora_fine TIME NOT NULL,
    tipo VARCHAR(100),
    stato VARCHAR(50),
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clienti(cliente_id)
        ON DELETE CASCADE
);

-- =====================================================
-- VISITE
-- =====================================================

CREATE TABLE visite (
    visita_id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    data_analisi DATE NOT NULL,
    note TEXT,
    FOREIGN KEY (cliente_id) REFERENCES clienti(cliente_id)
        ON DELETE CASCADE
);

-- =====================================================
-- SINTOMI
-- =====================================================

CREATE TABLE sintomi (
    sintomo_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descrizione TEXT
);

CREATE TABLE visita_sintomi (
    visita_id INT,
    sintomo_id INT,
    PRIMARY KEY (visita_id, sintomo_id),
    FOREIGN KEY (visita_id) REFERENCES visite(visita_id) ON DELETE CASCADE,
    FOREIGN KEY (sintomo_id) REFERENCES sintomi(sintomo_id) ON DELETE CASCADE
);

-- =====================================================
-- INTOLLERANZE
-- =====================================================

CREATE TABLE intolleranze (
    intolleranza_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descrizione TEXT
);

CREATE TABLE visita_intolleranze (
    visita_id INT,
    intolleranza_id INT,
    PRIMARY KEY (visita_id, intolleranza_id),
    FOREIGN KEY (visita_id) REFERENCES visite(visita_id) ON DELETE CASCADE,
    FOREIGN KEY (intolleranza_id) REFERENCES intolleranze(intolleranza_id) ON DELETE CASCADE
);

-- =====================================================
-- ALIMENTI
-- =====================================================

CREATE TABLE alimenti (
    alimento_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL
);

CREATE TABLE visita_alimenti (
    visita_id INT,
    alimento_id INT,
    PRIMARY KEY (visita_id, alimento_id),
    FOREIGN KEY (visita_id) REFERENCES visite(visita_id) ON DELETE CASCADE,
    FOREIGN KEY (alimento_id) REFERENCES alimenti(alimento_id) ON DELETE CASCADE
);

-- =====================================================
-- INTEGRATORI
-- =====================================================

CREATE TABLE integratori (
    integratore_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descrizione TEXT
);

CREATE TABLE visita_integratori (
    visita_id INT,
    integratore_id INT,
    PRIMARY KEY (visita_id, integratore_id),
    FOREIGN KEY (visita_id) REFERENCES visite(visita_id) ON DELETE CASCADE,
    FOREIGN KEY (integratore_id) REFERENCES integratori(integratore_id) ON DELETE CASCADE
);

-- =====================================================
-- QUESTIONARI / DOMANDE / RISPOSTE
-- =====================================================

CREATE TABLE questionari (
    questionario_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    attivo BOOLEAN DEFAULT TRUE
);

CREATE TABLE domande (
    domanda_id INT AUTO_INCREMENT PRIMARY KEY,
    questionario_id INT NOT NULL,
    testo TEXT NOT NULL,
    FOREIGN KEY (questionario_id) REFERENCES questionari(questionario_id)
        ON DELETE CASCADE
);

CREATE TABLE risposte (
    risposta_id INT AUTO_INCREMENT PRIMARY KEY,
    visita_id INT NOT NULL,
    domanda_id INT NOT NULL,
    risposta TEXT,
    FOREIGN KEY (visita_id) REFERENCES visite(visita_id)
        ON DELETE CASCADE,
    FOREIGN KEY (domanda_id) REFERENCES domande(domanda_id)
        ON DELETE CASCADE
);

-- =====================================================
-- SCHEDA FISICA
-- =====================================================

CREATE TABLE scheda_fisica (
    scheda_id INT AUTO_INCREMENT PRIMARY KEY,
    visita_id INT UNIQUE,
    massa_grassa DECIMAL(5,2),
    massa_magra DECIMAL(5,2),
    note TEXT,
    data DATE,
    FOREIGN KEY (visita_id) REFERENCES visite(visita_id)
        ON DELETE CASCADE
);

-- =====================================================
-- SCHEDA ANAMNESTICA
-- =====================================================

CREATE TABLE scheda_anamnestica (
    anamnesi_id INT AUTO_INCREMENT PRIMARY KEY,
    visita_id INT UNIQUE,
    osservazioni_finali TEXT,
    FOREIGN KEY (visita_id) REFERENCES visite(visita_id)
        ON DELETE CASCADE
);

-- =====================================================
-- STILE DI VITA
-- =====================================================

CREATE TABLE stile_vita (
    stile_id INT AUTO_INCREMENT PRIMARY KEY,
    anamnesi_id INT UNIQUE,
    alimentazione TEXT,
    attivita_fisica_tipo VARCHAR(150),
    attivita_fisica_frequenza VARCHAR(150),
    descrizione TEXT,
    FOREIGN KEY (anamnesi_id) REFERENCES scheda_anamnestica(anamnesi_id)
        ON DELETE CASCADE
);

-- =====================================================
-- ANAMNESI PERSONALI
-- =====================================================

CREATE TABLE anamnesi_personali (
    personale_id INT AUTO_INCREMENT PRIMARY KEY,
    anamnesi_id INT UNIQUE,
    allergie BOOLEAN,
    allergie_dettagli TEXT,
    interventi_chirurgici TEXT,
    patologie BOOLEAN,
    patologie_dettagli TEXT,
    alcol BOOLEAN,
    fumo BOOLEAN,
    farmaci_correnti TEXT,
    FOREIGN KEY (anamnesi_id) REFERENCES scheda_anamnestica(anamnesi_id)
        ON DELETE CASCADE
);

-- =====================================================
-- STATO PSICO-FISICO
-- =====================================================

CREATE TABLE stato_psico_fisico (
    stato_id INT AUTO_INCREMENT PRIMARY KEY,
    anamnesi_id INT UNIQUE,
    livello_stress INT,
    concentrazione INT,
    umore VARCHAR(100),
    ansia BOOLEAN,
    motivazione TEXT,
    note TEXT,
    FOREIGN KEY (anamnesi_id) REFERENCES scheda_anamnestica(anamnesi_id)
        ON DELETE CASCADE
);

-- =====================================================
-- QUALITA SONNO
-- =====================================================

CREATE TABLE qualita_sonno (
    sonno_id INT AUTO_INCREMENT PRIMARY KEY,
    anamnesi_id INT UNIQUE,
    ore_sonno DECIMAL(4,2),
    risvegli_notturni INT,
    qualita_percepita VARCHAR(100),
    difficolta_addormentarsi BOOLEAN,
    note TEXT,
    FOREIGN KEY (anamnesi_id) REFERENCES scheda_anamnestica(anamnesi_id)
        ON DELETE CASCADE
);

-- =====================================================
-- SUPPORTI UTILIZZATI (farmaci / rimedi / integratori)
-- =====================================================

CREATE TABLE supporti (
    supporto_id INT AUTO_INCREMENT PRIMARY KEY,
    categoria VARCHAR(100),
    descrizione TEXT
);

CREATE TABLE anamnesi_supporti (
    anamnesi_id INT,
    supporto_id INT,
    PRIMARY KEY (anamnesi_id, supporto_id),
    FOREIGN KEY (anamnesi_id) REFERENCES scheda_anamnestica(anamnesi_id)
        ON DELETE CASCADE,
    FOREIGN KEY (supporto_id) REFERENCES supporti(supporto_id)
        ON DELETE CASCADE
);
