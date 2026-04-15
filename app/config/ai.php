<?php
/**
 * Config base dell'integrazione AI (endpoint compatibile OpenAI).
 *
 * Esempio Groq:
 * - api_url: https://api.groq.com/openai/v1/chat/completions
 * - model: llama-3.1-8b-instant
 *
 * Puoi sovrascrivere i valori tramite variabili ambiente:
 * - AI_API_KEY
 * - AI_API_URL
 * - AI_MODEL
 * - AI_TIMEOUT
 */
return [
    // Chiave API di default (se c'e' una variabile ambiente, usa quella).
    'api_key' => getenv('AI_API_KEY') ?: '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',

    // Endpoint di default.
    'api_url' => getenv('AI_API_URL') ?: 'https://api.groq.com/openai/v1/chat/completions',

    // Modello leggero: va veloce e costa meno token.
    'model' => getenv('AI_MODEL') ?: 'llama-3.1-8b-instant',

    'timeout' => getenv('AI_TIMEOUT') ?: 40,
    'temperature' => 0.4,
];
