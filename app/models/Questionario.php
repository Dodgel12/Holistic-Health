<?php
/**
 * Modello Questionario.
 * Gestisce i questionari disponibili, le versioni
 * e l'attivazione o disattivazione degli stessi.
 */
namespace App\Models;

use App\Core\Database;

class Questionario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM questionari ORDER BY nome")->fetchAll();
    }

    public function getActive() {
        return $this->db->query("SELECT * FROM questionari WHERE attivo = 1 ORDER BY nome")->fetchAll();
    }

    public function getById($id) {
        return $this->db->query("SELECT * FROM questionari WHERE questionario_id = :id", ['id' => $id])->fetch();
    }

    public function create($nome) {
        $this->db->query("INSERT INTO questionari (nome, attivo) VALUES (:nome, 1)", ['nome' => $nome]);
        return $this->db->getConnection()->lastInsertId();
    }

    public function toggleActive($id) {
        $this->db->query("UPDATE questionari SET attivo = NOT attivo WHERE questionario_id = :id", ['id' => $id]);
    }
}
