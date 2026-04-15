<?php
/**
 * Client AI generico per endpoint chat completions compatibili OpenAI.
 */
namespace App\Core;

class AiClient
{
    private $config;

    public function __construct()
    {
        $configFile = BASE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'ai.php';
        $this->config = file_exists($configFile) ? require $configFile : [];
    }

    public function isConfigured()
    {
        return trim((string) ($this->config['api_key'] ?? '')) !== '';
    }

    public function getConfigHint()
    {
        return 'Configura la chiave API in app/config/ai.php (campo api_key) oppure nella variabile ambiente AI_API_KEY.';
    }

    public function generateClientTrendText(array $context)
    {
        if (!$this->isConfigured()) {
            throw new \RuntimeException($this->getConfigHint());
        }

        $apiUrl = trim((string) ($this->config['api_url'] ?? ''));
        $model = trim((string) ($this->config['model'] ?? ''));
        $apiKey = trim((string) ($this->config['api_key'] ?? ''));
        $timeout = (int) ($this->config['timeout'] ?? 40);
        $temperature = (float) ($this->config['temperature'] ?? 0.4);

        if ($apiUrl === '' || $model === '') {
            throw new \RuntimeException('Config AI incompleta: api_url o model mancanti in app/config/ai.php.');
        }

        $systemPrompt = 'Sei un assistente clinico per naturopatia. Scrivi in italiano, stile professionale e chiaro. Produci solo testo semplice (no markdown). Non inventare dati. Evidenzia trend, segnali importanti e 3 consigli pratici finali. Mantieni il testo tra 120 e 220 parole.';

        $userPrompt = "Analizza il seguente contesto cliente in JSON e genera un riepilogo andamento:\n\n" . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $payload = [
            'model' => $model,
            'temperature' => $temperature,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt],
            ],
        ];

        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => max(10, $timeout),
        ]);

        $raw = curl_exec($ch);
        $curlErr = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($raw === false) {
            throw new \RuntimeException('Errore connessione AI: ' . $curlErr);
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            throw new \RuntimeException('Risposta AI non valida (JSON non parseabile).');
        }

        if ($status >= 400) {
            $errMsg = $data['error']['message'] ?? ('HTTP ' . $status);
            throw new \RuntimeException('Errore AI: ' . $errMsg);
        }

        $text = trim((string) ($data['choices'][0]['message']['content'] ?? ''));
        if ($text === '') {
            throw new \RuntimeException('Risposta AI vuota.');
        }

        return $text;
    }
}
