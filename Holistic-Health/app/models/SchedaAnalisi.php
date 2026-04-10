<?php
/**
 * Modello SchedaAnalisi.
 * Gestisce visite e dati fisici con tutti i parametri.
 */
namespace App\Models;

use App\Core\Database;

class SchedaAnalisi {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createVisita($clienteId, $data, $note = '') {
        $this->db->query(
            "INSERT INTO visite (cliente_id, data_analisi, note) VALUES (:cliente_id, :data_analisi, :note)",
            ['cliente_id' => $clienteId, 'data_analisi' => $data, 'note' => $note]
        );
        return $this->db->getConnection()->lastInsertId();
    }

    public function getVisiteByClient($clienteId) {
        return $this->db->query(
            "SELECT * FROM visite WHERE cliente_id = :id ORDER BY data_analisi DESC",
            ['id' => $clienteId]
        )->fetchAll();
    }

    /** Visite con dati fisici inclusi (per storico) */
    public function getVisiteByClientWithFisica($clienteId) {
        $visite = $this->getVisiteByClient($clienteId);
        foreach ($visite as &$v) {
            $fisica = $this->db->query(
                "SELECT * FROM scheda_fisica WHERE visita_id = :id",
                ['id' => $v['visita_id']]
            )->fetch();
            $v['fisica'] = $fisica ?: [];
        }
        unset($v);
        return $visite;
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

            // Aggiunta: recupera anche i dati anamnestici se presenti
            $schedaAnamnestica = new SchedaAnamnestica();
            $visita['anamnesi'] = $schedaAnamnestica->getByVisitaId($id);
        }
        return $visita;
    }

    public function saveFisica($visitaId, $data) {
        $sql = "INSERT INTO scheda_fisica 
                    (visita_id, massa_grassa, note, data, peso, altezza, acqua_corporea,
                     metabolismo_basale, eta_metabolica, grasso_viscerale, massa_ossea)
                VALUES 
                    (:visita_id, :massa_grassa, :note, :data, :peso, :altezza, :acqua_corporea,
                     :metabolismo_basale, :eta_metabolica, :grasso_viscerale, :massa_ossea)
                ON DUPLICATE KEY UPDATE
                    massa_grassa        = VALUES(massa_grassa),
                    note                = VALUES(note),
                    peso                = VALUES(peso),
                    altezza             = VALUES(altezza),
                    acqua_corporea      = VALUES(acqua_corporea),
                    metabolismo_basale  = VALUES(metabolismo_basale),
                    eta_metabolica      = VALUES(eta_metabolica),
                    grasso_viscerale    = VALUES(grasso_viscerale),
                    massa_ossea         = VALUES(massa_ossea)";

        $this->db->query($sql, [
            'visita_id'          => $visitaId,
            'massa_grassa'       => $data['massa_grassa'] ?? null,
            'note'               => $data['note'] ?? '',
            'data'               => date('Y-m-d'),
            'peso'               => $data['peso'] ?? null,
            'altezza'            => $data['altezza'] ?? null,
            'acqua_corporea'     => $data['acqua_corporea'] ?? null,
            'metabolismo_basale' => $data['metabolismo_basale'] ?? null,
            'eta_metabolica'     => $data['eta_metabolica'] ?? null,
            'grasso_viscerale'   => $data['grasso_viscerale'] ?? null,
            'massa_ossea'        => $data['massa_ossea'] ?? null,
        ]);
    }
}
