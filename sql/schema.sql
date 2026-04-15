-- Schema principale del progetto Holistic Health.
-- Puoi rilanciarlo piu' volte: crea solo quello che manca.
-- Non usa DROP: evita cancellazioni accidentali.

CREATE DATABASE IF NOT EXISTS gestionale_studio
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE gestionale_studio;

-- =====================================================
-- AUTENTICAZIONE / UTENTI
-- =====================================================

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NULL,
    must_change_password TINYINT(1) NOT NULL DEFAULT 1,
    security_question VARCHAR(255) NULL,
    security_answer_hash VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS password_reset_tokens (
    token_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    INDEX idx_password_reset_user (user_id),
    INDEX idx_password_reset_expiry (expires_at),
    CONSTRAINT fk_password_reset_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- CLIENTI / APPUNTAMENTI / VISITE
-- =====================================================

CREATE TABLE IF NOT EXISTS clienti (
    cliente_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100) NOT NULL,
    data_nascita DATE NULL,
    professione VARCHAR(150) NULL,
    telefono VARCHAR(50) NULL,
    email VARCHAR(150) NULL,
    indirizzo VARCHAR(255) NULL,
    INDEX idx_clienti_nome (nome, cognome)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS appuntamenti (
    appuntamento_id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    data DATE NOT NULL,
    ora_inizio TIME NOT NULL,
    ora_fine TIME NOT NULL,
    tipo VARCHAR(100) NULL,
    stato VARCHAR(50) NULL,
    note TEXT NULL,
    INDEX idx_appuntamenti_cliente (cliente_id),
    INDEX idx_appuntamenti_data (data, ora_inizio),
    CONSTRAINT fk_appuntamenti_cliente
        FOREIGN KEY (cliente_id) REFERENCES clienti(cliente_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS visite (
    visita_id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    data_analisi DATE NOT NULL,
    tipo_visita VARCHAR(30) NOT NULL DEFAULT 'anamnestica',
    note TEXT NULL,
    INDEX idx_visite_cliente (cliente_id),
    INDEX idx_visite_data (data_analisi, visita_id),
    CONSTRAINT fk_visite_cliente
        FOREIGN KEY (cliente_id) REFERENCES clienti(cliente_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- CATALOGHI DI BASE
-- =====================================================

CREATE TABLE IF NOT EXISTS alimenti (
    alimento_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    UNIQUE KEY uk_alimenti_nome (nome)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS integratori (
    integratore_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descrizione TEXT NULL,
    UNIQUE KEY uk_integratori_nome (nome)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS farmaci (
    farmaco_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descrizione TEXT NULL,
    UNIQUE KEY uk_farmaci_nome (nome)
) ENGINE=InnoDB;

-- Collegamenti tra visite e cataloghi
CREATE TABLE IF NOT EXISTS visita_alimenti (
    visita_id INT NOT NULL,
    alimento_id INT NOT NULL,
    PRIMARY KEY (visita_id, alimento_id),
    CONSTRAINT fk_visita_alimenti_visita
        FOREIGN KEY (visita_id) REFERENCES visite(visita_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_visita_alimenti_alimento
        FOREIGN KEY (alimento_id) REFERENCES alimenti(alimento_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS visita_integratori (
    visita_id INT NOT NULL,
    integratore_id INT NOT NULL,
    PRIMARY KEY (visita_id, integratore_id),
    CONSTRAINT fk_visita_integratori_visita
        FOREIGN KEY (visita_id) REFERENCES visite(visita_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_visita_integratori_integratore
        FOREIGN KEY (integratore_id) REFERENCES integratori(integratore_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- PIANI TERAPEUTICI
-- =====================================================

CREATE TABLE IF NOT EXISTS piani_terapeutici (
    piano_id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    titolo VARCHAR(180) NOT NULL,
    obiettivi TEXT NULL,
    note TEXT NULL,
    stato VARCHAR(30) NOT NULL DEFAULT 'Attivo',
    data_inizio DATE NOT NULL,
    data_fine DATE NULL,
    INDEX idx_piani_cliente (cliente_id),
    INDEX idx_piani_data (data_inizio, piano_id),
    CONSTRAINT fk_piani_cliente
        FOREIGN KEY (cliente_id) REFERENCES clienti(cliente_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS piano_alimenti (
    piano_id INT NOT NULL,
    alimento_id INT NOT NULL,
    PRIMARY KEY (piano_id, alimento_id),
    CONSTRAINT fk_piano_alimenti_piano
        FOREIGN KEY (piano_id) REFERENCES piani_terapeutici(piano_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_piano_alimenti_alimento
        FOREIGN KEY (alimento_id) REFERENCES alimenti(alimento_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS piano_integratori (
    piano_id INT NOT NULL,
    integratore_id INT NOT NULL,
    PRIMARY KEY (piano_id, integratore_id),
    CONSTRAINT fk_piano_integratori_piano
        FOREIGN KEY (piano_id) REFERENCES piani_terapeutici(piano_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_piano_integratori_integratore
        FOREIGN KEY (integratore_id) REFERENCES integratori(integratore_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS piano_farmaci (
    piano_id INT NOT NULL,
    farmaco_id INT NOT NULL,
    PRIMARY KEY (piano_id, farmaco_id),
    CONSTRAINT fk_piano_farmaci_piano
        FOREIGN KEY (piano_id) REFERENCES piani_terapeutici(piano_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_piano_farmaci_farmaco
        FOREIGN KEY (farmaco_id) REFERENCES farmaci(farmaco_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- DOMANDE ANAMNESI
-- =====================================================

CREATE TABLE IF NOT EXISTS domande_impostazioni (
    domanda_id INT AUTO_INCREMENT PRIMARY KEY,
    testo TEXT NOT NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS risposte (
    risposta_id INT AUTO_INCREMENT PRIMARY KEY,
    visita_id INT NOT NULL,
    domanda_testo TEXT NOT NULL,
    risposta TEXT NULL,
    INDEX idx_risposte_visita (visita_id),
    CONSTRAINT fk_risposte_visita
        FOREIGN KEY (visita_id) REFERENCES visite(visita_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- SCHEDA FISICA
-- =====================================================

CREATE TABLE IF NOT EXISTS scheda_fisica (
    scheda_id INT AUTO_INCREMENT PRIMARY KEY,
    visita_id INT NOT NULL,
    peso DECIMAL(5,2) NULL,
    altezza DECIMAL(5,2) NULL,
    UNIQUE KEY uk_scheda_fisica_visita (visita_id),
    CONSTRAINT fk_scheda_fisica_visita
        FOREIGN KEY (visita_id) REFERENCES visite(visita_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- SCHEDA ANAMNESTICA
-- =====================================================

CREATE TABLE IF NOT EXISTS scheda_anamnestica (
    anamnesi_id INT AUTO_INCREMENT PRIMARY KEY,
    visita_id INT NOT NULL,
    osservazioni_finali TEXT NULL,
    UNIQUE KEY uk_scheda_anamnestica_visita (visita_id),
    CONSTRAINT fk_scheda_anamnestica_visita
        FOREIGN KEY (visita_id) REFERENCES visite(visita_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS stile_vita (
    stile_id INT AUTO_INCREMENT PRIMARY KEY,
    anamnesi_id INT NOT NULL,
    alimentazione TEXT NULL,
    attivita_fisica_tipo VARCHAR(150) NULL,
    attivita_fisica_frequenza VARCHAR(150) NULL,
    descrizione TEXT NULL,
    UNIQUE KEY uk_stile_vita_anamnesi (anamnesi_id),
    CONSTRAINT fk_stile_vita_anamnesi
        FOREIGN KEY (anamnesi_id) REFERENCES scheda_anamnestica(anamnesi_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS anamnesi_personali (
    personale_id INT AUTO_INCREMENT PRIMARY KEY,
    anamnesi_id INT NOT NULL,
    allergie TINYINT(1) NULL,
    allergie_dettagli TEXT NULL,
    interventi_chirurgici TEXT NULL,
    patologie TINYINT(1) NULL,
    patologie_dettagli TEXT NULL,
    alcol TINYINT(1) NULL,
    fumo TINYINT(1) NULL,
    farmaci_correnti TEXT NULL,
    UNIQUE KEY uk_anamnesi_personali_anamnesi (anamnesi_id),
    CONSTRAINT fk_anamnesi_personali_anamnesi
        FOREIGN KEY (anamnesi_id) REFERENCES scheda_anamnestica(anamnesi_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS stato_psico_fisico (
    stato_id INT AUTO_INCREMENT PRIMARY KEY,
    anamnesi_id INT NOT NULL,
    livello_stress INT NULL,
    concentrazione INT NULL,
    umore VARCHAR(100) NULL,
    ansia TINYINT(1) NULL,
    motivazione TEXT NULL,
    UNIQUE KEY uk_stato_psico_fisico_anamnesi (anamnesi_id),
    CONSTRAINT fk_stato_psico_fisico_anamnesi
        FOREIGN KEY (anamnesi_id) REFERENCES scheda_anamnestica(anamnesi_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS qualita_sonno (
    sonno_id INT AUTO_INCREMENT PRIMARY KEY,
    anamnesi_id INT NOT NULL,
    ore_sonno DECIMAL(4,2) NULL,
    risvegli_notturni INT NULL,
    qualita_percepita VARCHAR(100) NULL,
    difficolta_addormentarsi TINYINT(1) NULL,
    UNIQUE KEY uk_qualita_sonno_anamnesi (anamnesi_id),
    CONSTRAINT fk_qualita_sonno_anamnesi
        FOREIGN KEY (anamnesi_id) REFERENCES scheda_anamnestica(anamnesi_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- Nota: l'utente admin iniziale viene creato da public/init_db.php.

