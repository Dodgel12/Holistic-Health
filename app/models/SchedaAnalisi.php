<?php
/**
 * Modello SchedaAnalisi.
 * Rappresenta una singola analisi effettuata
 * su un cliente e il collegamento al questionario usato.
 */
namespace App\Models;

use App\Core\Database;

class SchedaAnalisi {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createVisita($clienteId, $data, $note = '') {
        $this->db->query("INSERT INTO visite (cliente_id, data_analisi, note) VALUES (:cliente_id, :data_analisi, :note)", [
            'cliente_id' => $clienteId,
            'data_analisi' => $data,
            'note' => $note
        ]);
        return $this->db->getConnection()->lastInsertId();
    }

    public function getVisiteByClient($clienteId) {
        return $this->db->query("SELECT * FROM visite WHERE cliente_id = :id ORDER BY data_analisi DESC", ['id' => $clienteId])->fetchAll();
    }
    
    public function getVisitaById($id) {
        $visita = $this->db->query("SELECT * FROM visite WHERE visita_id = :id", ['id' => $id])->fetch();
        if ($visita) {
            $visita['scheda_fisica'] = $this->db->query("SELECT * FROM scheda_fisica WHERE visita_id = :id", ['id' => $id])->fetch();
        }
        return $visita;
    }

    public function saveFisica($visitaId, $data) {
        $sql = "INSERT INTO scheda_fisica (visita_id, massa_grassa, massa_magra, note, data) 
                VALUES (:visita_id, :massa_grassa, :massa_magra, :note, :data)
                ON DUPLICATE KEY UPDATE 
                massa_grassa = VALUES(massa_grassa), 
                massa_magra = VALUES(massa_magra), 
                note = VALUES(note)";
        
        $this->db->query($sql, [
            'visita_id' => $visitaId,
            'massa_grassa' => $data['massa_grassa'] ?? 0,
            'massa_magra' => $data['massa_magra'] ?? 0,
            'note' => $data['note'] ?? '',
            'data' => date('Y-m-d')
        ]);
    }
}
