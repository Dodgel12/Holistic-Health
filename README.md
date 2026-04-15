# Holistic-Health

Applicazione web per la gestione dell'attività professionale naturopatica, sviluppata con stack PHP/MySQL e architettura MVC-like.

## Panoramica

Holistic-Health consente di centralizzare in un'unica piattaforma:

- anagrafica clienti;
- storico anamnestico e fisico;
- questionari e risposte;
- appuntamenti;
- riepiloghi di andamento supportati da Assistente AI.

L'applicazione è pensata per utilizzo single-operator, con accesso autenticato tramite sessione.

## Stack Tecnologico

- Backend: PHP
- Database: MySQL (PDO)
- Frontend: HTML, CSS, JavaScript
- Server applicativo: Apache (es. XAMPP)
- Architettura: MVC-like senza framework full-stack

## Funzionalità Principali

- autenticazione (login/logout);
- gestione pazienti (CRUD);
- compilazione schede anamnestiche;
- compilazione schede fisiche;
- gestione appuntamenti;
- gestione domande/questionari;
- storico visite e indicatori sintetici;
- sezione Assistente AI per generazione automatica dell'andamento cliente.

## Struttura Progetto

- public/: entry point HTTP e router pagine
- app/config/: configurazioni (database, AI)
- app/core/: componenti base (database, auth, servizi)
- app/models/: logica dati e query
- app/controllers/: logica applicativa
- app/views/: interfaccia utente
- sql/: schema e script database

## Setup Locale

1. Importare [sql/schema.sql](sql/schema.sql) nel database MySQL.
2. Configurare la connessione DB in [app/config/database.php](app/config/database.php).
3. Avviare Apache/MySQL (es. XAMPP).
4. Aprire l'app nel browser tramite la cartella public.

## Assistente AI

Nella cartella cliente è presente il pulsante Andamento cliente (AI), che genera un testo sintetico analizzando i dati disponibili.

### Configurazione

Modificare [app/config/ai.php](app/config/ai.php):

- api_key: chiave del provider AI
- api_url: endpoint chat completions
- model: modello da utilizzare
- timeout: timeout richiesta

In alternativa, usare variabili ambiente:

- AI_API_KEY
- AI_API_URL
- AI_MODEL
- AI_TIMEOUT

### Endpoint consigliato per Groq

Se utilizzi Groq, usa:

- https://api.groq.com/openai/v1/chat/completions

### Modello consigliato per ridurre i token

Per mantenere costi e latenza contenuti, un buon default è:

- llama-3.1-8b-instant

Alternative orientate al risparmio:

- llama-3.1-8b-instant (consigliato)
- llama-3.1-70b-versatile (più qualità, maggiore costo)

### Fallback automatico

Se la API non è configurata o la richiesta fallisce, l'app mostra automaticamente il riepilogo locale calcolato internamente.
