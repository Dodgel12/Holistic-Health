<?php
/**
 * Modello Appuntamento.
 * Gestisce gli appuntamenti tra la naturopata
 * e i clienti, inclusi data, durata e stato.
 */
namespace App\Models;

use App\Core\Database;

class Appuntamento {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT a.*, c.nome, c.cognome 
                FROM appuntamenti a 
                JOIN clienti c ON a.cliente_id = c.cliente_id 
                ORDER BY a.data, a.ora_inizio";
        return $this->db->query($sql)->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO appuntamenti (cliente_id, data, ora_inizio, ora_fine, tipo, stato, note) 
                VALUES (:cliente_id, :data, :ora_inizio, :ora_fine, :tipo, :stato, :note)";
        $this->db->query($sql, [
            'cliente_id' => $data['cliente_id'],
            'data' => $data['data'],
            'ora_inizio' => $data['ora_inizio'],
            'ora_fine' => $data['ora_fine'],
            'tipo' => $data['tipo'] ?? 'Visita',
            'stato' => $data['stato'] ?? 'Programmato',
            'note' => $data['note'] ?? ''
        ]);
        return $this->db->getConnection()->lastInsertId();
    }

    public function delete($id) {
        return $this->db->query("DELETE FROM appuntamenti WHERE appuntamento_id = :id", ['id' => $id]);
    }
}
