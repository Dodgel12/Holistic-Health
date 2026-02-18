<?php
/**
 * Modello SchedaAnamnestica.
 * Gestisce i dati anamnestici e di stile di vita
 * del cliente, mantenendo lo storico delle compilazioni.
 */
namespace App\Models;

use App\Core\Database;

class SchedaAnamnestica {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getByVisitaId($visitaId) {
        $stmt = $this->db->query("SELECT * FROM scheda_anamnestica WHERE visita_id = :visita_id", ['visita_id' => $visitaId]);
        $scheda = $stmt->fetch();
        
        if ($scheda) {
            // Caricamento dati tabelle correlate
            $scheda['stile_vita'] = $this->db->query("SELECT * FROM stile_vita WHERE anamnesi_id = :id", ['id' => $scheda['anamnesi_id']])->fetch();
            $scheda['personale'] = $this->db->query("SELECT * FROM anamnesi_personali WHERE anamnesi_id = :id", ['id' => $scheda['anamnesi_id']])->fetch();
            $scheda['psico_fisico'] = $this->db->query("SELECT * FROM stato_psico_fisico WHERE anamnesi_id = :id", ['id' => $scheda['anamnesi_id']])->fetch();
            $scheda['sonno'] = $this->db->query("SELECT * FROM qualita_sonno WHERE anamnesi_id = :id", ['id' => $scheda['anamnesi_id']])->fetch();
        }
        
        return $scheda;
    }

    public function create($visitaId, $data) {
        // Logica di creazione base
        $this->db->query("INSERT INTO scheda_anamnestica (visita_id, osservazioni_finali) VALUES (:visita_id, :osservazioni)", [
            'visita_id' => $visitaId,
            'osservazioni' => $data['osservazioni_finali'] ?? ''
        ]);
        $anamnesiId = $this->db->getConnection()->lastInsertId();
        
        // L'inserimento dei dati correlati andrebbe qui...
        return $anamnesiId;
    }
}