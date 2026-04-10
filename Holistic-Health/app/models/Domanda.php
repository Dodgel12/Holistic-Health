<?php
/**
 * Modello Domanda.
 * Rappresenta una singola domanda utilizzabile
 * all'interno di uno o più questionari.
 */
namespace App\Models;

use App\Core\Database;

class Domanda {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getByQuestionario($questionarioId) {
        return $this->db->query("SELECT * FROM domande WHERE questionario_id = :id ORDER BY domanda_id", ['id' => $questionarioId])->fetchAll();
    }

    public function create($questionarioId, $testo) {
        $this->db->query("INSERT INTO domande (questionario_id, testo) VALUES (:id, :testo)", [
            'id' => $questionarioId,
            'testo' => $testo
        ]);
        return $this->db->getConnection()->lastInsertId();
    }

    public function delete($id) {
        $this->db->query("DELETE FROM domande WHERE domanda_id = :id", ['id' => $id]);
    }
}
