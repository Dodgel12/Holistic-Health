<?php
/**
 * Genera un riepilogo andamento cliente con logica locale.
 */
namespace App\Models;

use App\Core\Database;

class ClienteInsights
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function buildSummary($clienteId)
    {
        $visite = $this->db->query(
            "SELECT v.visita_id, v.data_analisi, sf.peso
             FROM visite v
             LEFT JOIN scheda_fisica sf ON sf.visita_id = v.visita_id
             WHERE v.cliente_id = :id
             ORDER BY v.data_analisi ASC, v.visita_id ASC",
            ['id' => $clienteId]
        )->fetchAll();

        if (empty($visite)) {
            return 'Non ci sono ancora visite registrate: impossibile generare un riepilogo andamento.';
        }

        $totVisite = count($visite);
        $first = $visite[0];
        $last = $visite[$totVisite - 1];

        $parts = [];
        $parts[] = "Sono presenti {$totVisite} visite registrate, dalla prima del " . date('d/m/Y', strtotime($first['data_analisi'])) . " all'ultima del " . date('d/m/Y', strtotime($last['data_analisi'])) . ".";

        $pesoFirst = $this->firstNumeric($visite, 'peso');
        $pesoLast = $this->lastNumeric($visite, 'peso');
        if ($pesoFirst !== null && $pesoLast !== null) {
            $diff = round($pesoLast - $pesoFirst, 1);
            if ($diff > 0) {
                $parts[] = "Il peso mostra un incremento di {$diff} kg rispetto alla prima rilevazione.";
            } elseif ($diff < 0) {
                $parts[] = "Il peso mostra una riduzione di " . abs($diff) . " kg rispetto alla prima rilevazione.";
            } else {
                $parts[] = "Il peso risulta stabile rispetto alla prima rilevazione.";
            }
        } else {
            $parts[] = 'I dati peso non sono sufficienti per stimare un trend affidabile.';
        }

        $stressAvg = $this->db->query(
            "SELECT AVG(spf.livello_stress) AS avg_stress
             FROM visite v
             JOIN scheda_anamnestica sa ON sa.visita_id = v.visita_id
             JOIN stato_psico_fisico spf ON spf.anamnesi_id = sa.anamnesi_id
             WHERE v.cliente_id = :id",
            ['id' => $clienteId]
        )->fetch();

        if (!empty($stressAvg['avg_stress'])) {
            $avgStress = round((float) $stressAvg['avg_stress'], 1);
            $parts[] = "Il livello medio di stress rilevato nelle anamnesi è {$avgStress}/10.";
        }

        $parts[] = 'Suggerimento operativo: validare i trend con il piano terapeutico attivo e con le note cliniche dell\'ultima visita.';

        return implode(' ', $parts);
    }

    /**
     * Prepara un contesto compatto da inviare a provider AI esterni.
     */
    public function buildAiContext($clienteId, $maxVisits = 12)
    {
        $clienteId = (int) $clienteId;
        $maxVisits = max(3, min(30, (int) $maxVisits));

        $client = $this->db->query(
            "SELECT cliente_id, nome, cognome, data_nascita, professione
             FROM clienti
             WHERE cliente_id = :id
             LIMIT 1",
            ['id' => $clienteId]
        )->fetch();

        $visite = $this->db->query(
            "SELECT v.visita_id,
                    v.data_analisi,
                    v.tipo_visita,
                    v.note,
                    sf.peso,
                    spf.livello_stress,
                    spf.concentrazione,
                    spf.umore,
                    qs.ore_sonno,
                    qs.qualita_percepita
             FROM visite v
             LEFT JOIN scheda_fisica sf ON sf.visita_id = v.visita_id
             LEFT JOIN scheda_anamnestica sa ON sa.visita_id = v.visita_id
             LEFT JOIN stato_psico_fisico spf ON spf.anamnesi_id = sa.anamnesi_id
             LEFT JOIN qualita_sonno qs ON qs.anamnesi_id = sa.anamnesi_id
             WHERE v.cliente_id = :id
             ORDER BY v.data_analisi DESC, v.visita_id DESC
             LIMIT {$maxVisits}",
            ['id' => $clienteId]
        )->fetchAll();

        // Rimette le visite in ordine cronologico per leggere meglio il trend.
        $visite = array_reverse($visite);

        return [
            'cliente' => [
                'cliente_id' => (int) ($client['cliente_id'] ?? $clienteId),
                'nome' => $client['nome'] ?? '',
                'cognome' => $client['cognome'] ?? '',
                'data_nascita' => $client['data_nascita'] ?? null,
                'professione' => $client['professione'] ?? null,
            ],
            'totale_visite_nel_contesto' => count($visite),
            'visite' => $visite,
            'riepilogo_locale' => $this->buildSummary($clienteId),
        ];
    }

    private function firstNumeric($rows, $key)
    {
        foreach ($rows as $r) {
            if (isset($r[$key]) && $r[$key] !== null && $r[$key] !== '') {
                return (float) $r[$key];
            }
        }
        return null;
    }

    private function lastNumeric($rows, $key)
    {
        for ($i = count($rows) - 1; $i >= 0; $i--) {
            if (isset($rows[$i][$key]) && $rows[$i][$key] !== null && $rows[$i][$key] !== '') {
                return (float) $rows[$i][$key];
            }
        }
        return null;
    }
}
