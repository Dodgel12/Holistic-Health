Holisitc-Health App Management APP

Applicazione web sviluppata con stack LAMP (Linux, Apache, MySQL, PHP) per la gestione dell’attività di una naturopata.
L’applicazione è pensata per un solo operatore e consente di gestire clienti, anamnesi, analisi, questionari e appuntamenti in modo strutturato e sicuro.

**Obiettivo del progetto**

Fornire uno strumento semplice ma completo per:

gestire i dati dei clienti

compilare schede anamnestiche e di stile di vita

effettuare analisi basate su questionari

pianificare e consultare appuntamenti

mantenere uno storico ordinato delle informazioni

**Tecnologie utilizzate**

Backend: PHP

Database: MySQL (PDO)

Server: Apache

Frontend: HTML, CSS, JavaScript

Architettura: MVC-like (senza framework)

**Utenti**

Un solo utente: la naturopata

Accesso protetto tramite login e sessione

Nessuna gestione di ruoli o multi-utenza

**Funzionalità principali**

Autenticazione (login / logout)

Gestione clienti (CRUD)

Scheda anamnestica e stile di vita (storico)

Schede di analisi basate su questionari

Gestione questionari e domande

Registrazione delle risposte

Gestione appuntamenti

Dashboard riepilogativa

Struttura del progetto:

public/ → pagine accessibili dal browser

app/

config/ → configurazione database

core/ → classi base (Database, Auth)

models/ → logica dei dati

controllers/ → logica applicativa

views/ → interfaccia utente

sql/ → schema del database

**Database**

Il database è progettato secondo un modello relazionale normalizzato, con:

separazione tra clienti, anamnesi, analisi e questionari

gestione delle relazioni molti-a-molti tramite tabelle associative

storico delle analisi e delle compilazioni

Lo schema completo è disponibile in:

sql/schema.sql

**Avvio del progetto** 

Importare schema.sql in MySQL

Configurare le credenziali in app/config/database.php

Avviare Apache

Accedere all’app dal browser

Effettuare il login come naturopata