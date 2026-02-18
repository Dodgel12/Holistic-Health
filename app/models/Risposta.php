<?php
/**
 * Modello Risposta.
 * Gestisce le risposte fornite durante una scheda
 * di analisi, collegandole a domande e analisi specifiche.
 */
namespace App\Models;

use App\Core\Database;

class Risposta {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function save($visitaId, $domandaId, $risposta) {
        $sql = "INSERT INTO risposte (visita_id, domanda_id, risposta) 
                VALUES (:visita_id, :domanda_id, :risposta)
                ON DUPLICATE KEY UPDATE risposta = VALUES(risposta)";
        
        $this->db->query($sql, [
            'visita_id' => $visitaId,
            'domanda_id' => $domandaId,
            'risposta' => $risposta
        ]);
    }

    public function getByVisita($visitaId) {
        $sql = "SELECT r.*, d.testo as domanda_testo 
                FROM risposte r 
                JOIN domande d ON r.domanda_id = d.domanda_id 
                WHERE r.visita_id = :id";
        return $this->db->query($sql, ['id' => $visitaId])->fetchAll();
    }
}
