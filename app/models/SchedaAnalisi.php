<?php
/**
 * Modello scheda analisi.
 * Gestisce visite e dati fisici.
 */
namespace App\Models;

use App\Core\Database;

class SchedaAnalisi {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createVisita($clienteId, $data, $note = '', $tipoVisita = 'anamnestica') {
        $this->db->query(
            "INSERT INTO visite (cliente_id, data_analisi, tipo_visita, note) VALUES (:cliente_id, :data_analisi, :tipo_visita, :note)",
            ['cliente_id' => $clienteId, 'data_analisi' => $data, 'tipo_visita' => $tipoVisita, 'note' => $note]
        );
        return $this->db->getConnection()->lastInsertId();
    }

    public function updateVisita($visitaId, $clienteId, $data, $note = '', $tipoVisita = 'anamnestica') {
        return $this->db->query(
            "UPDATE visite
             SET data_analisi = :data_analisi,
                 tipo_visita = :tipo_visita,
                 note = :note
             WHERE visita_id = :visita_id AND cliente_id = :cliente_id",
            [
                'visita_id' => $visitaId,
                'cliente_id' => $clienteId,
                'data_analisi' => $data,
                'tipo_visita' => $tipoVisita,
                'note' => $note
            ]
        );
    }

    public function getVisiteByClient($clienteId) {
        return $this->db->query(
            "SELECT * FROM visite WHERE cliente_id = :id ORDER BY data_analisi DESC, visita_id DESC",
            ['id' => $clienteId]
        )->fetchAll();
    }

    /** Visite con dati fisici inclusi, utili per lo storico. */
    public function getVisiteByClientWithFisica($clienteId) {
        $visite = $this->getVisiteByClient($clienteId);
        foreach ($visite as &$v) {
            $fisica = $this->db->query(
                "SELECT * FROM scheda_fisica WHERE visita_id = :id",
                ['id' => $v['visita_id']]
            )->fetch();
            $v['fisica'] = $fisica ?: [];

            $v['anamnesi_snapshot'] = $this->getLatestAnamnesiSnapshotUntilVisita(
                (int) $clienteId,
                $v['data_analisi'],
                (int) $v['visita_id']
            );
        }
        unset($v);
        return $visite;
    }

    public function getLatestFisicaByClient($clienteId) {
        return $this->db->query(
            "SELECT v.visita_id, v.data_analisi, sf.*
             FROM visite v
             JOIN scheda_fisica sf ON sf.visita_id = v.visita_id
             WHERE v.cliente_id = :id
             ORDER BY v.data_analisi DESC, v.visita_id DESC
             LIMIT 1",
            ['id' => $clienteId]
        )->fetch() ?: null;
    }

    public function getVisitaById($id) {
        $visita = $this->db->query(
            "SELECT * FROM visite WHERE visita_id = :id",
            ['id' => $id]
        )->fetch();
        if ($visita) {
            $visita['scheda_fisica'] = $this->db->query(
                "SELECT * FROM scheda_fisica WHERE visita_id = :id",
                ['id' => $id]
            )->fetch() ?: [];

            // Recupera anche i dati anamnestici, se presenti.
            $schedaAnamnestica = new SchedaAnamnestica();
            $visita['anamnesi'] = $schedaAnamnestica->getByVisitaId($id);
        }
        return $visita;
    }

    public function getLatestAnamnesiSnapshotUntilVisita($clienteId, $dataAnalisi, $visitaId)
    {
        $row = $this->db->query(
                        "SELECT s.visita_id
             FROM scheda_anamnestica s
             JOIN visite v ON v.visita_id = s.visita_id
             WHERE v.cliente_id = :cliente_id
               AND (
                    v.data_analisi < :data_analisi
                    OR (v.data_analisi = :data_analisi AND v.visita_id <= :visita_id)
               )
             ORDER BY v.data_analisi DESC, v.visita_id DESC
             LIMIT 1",
            [
                'cliente_id' => $clienteId,
                'data_analisi' => $dataAnalisi,
                'visita_id' => $visitaId
            ]
        )->fetch();

        if (!$row) {
            return null;
        }

        $schedaAnamnestica = new SchedaAnamnestica();
        return $schedaAnamnestica->getByVisitaId((int) $row['visita_id']);
    }

    public function getLatestFisicaSnapshotUntilVisita($clienteId, $dataAnalisi, $visitaId)
    {
        return $this->db->query(
            "SELECT v.visita_id, v.data_analisi, sf.*
             FROM visite v
             JOIN scheda_fisica sf ON sf.visita_id = v.visita_id
             WHERE v.cliente_id = :cliente_id
               AND (
                    v.data_analisi < :data_analisi
                    OR (v.data_analisi = :data_analisi AND v.visita_id <= :visita_id)
               )
             ORDER BY v.data_analisi DESC, v.visita_id DESC
             LIMIT 1",
            [
                'cliente_id' => $clienteId,
                'data_analisi' => $dataAnalisi,
                'visita_id' => $visitaId
            ]
        )->fetch() ?: null;
    }

    public function deleteVisita($visitaId, $clienteId)
    {
        return $this->db->query(
            "DELETE FROM visite WHERE visita_id = :visita_id AND cliente_id = :cliente_id",
            [
                'visita_id' => $visitaId,
                'cliente_id' => $clienteId
            ]
        );
    }

    public function saveFisica($visitaId, $data) {
        $sql = "INSERT INTO scheda_fisica 
                    (visita_id, peso, altezza)
                VALUES 
                    (:visita_id, :peso, :altezza)
                ON DUPLICATE KEY UPDATE
                    peso                = VALUES(peso),
                    altezza             = VALUES(altezza)";

        $this->db->query($sql, [
            'visita_id'          => $visitaId,
            'peso'               => $data['peso'] ?? null,
            'altezza'            => $data['altezza'] ?? null,
        ]);
    }

    public function deleteFisicaByVisita($visitaId) {
        return $this->db->query("DELETE FROM scheda_fisica WHERE visita_id = :id", ['id' => $visitaId]);
    }
}
