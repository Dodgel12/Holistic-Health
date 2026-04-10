<?php
/**
 * Modello Client.
 * CRUD sui dati anagrafici dei clienti.
 */
namespace App\Models;

use App\Core\Database;
use PDO;

class Client {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /** Tutti i clienti con data ultima visita */
    public function getAllWithLastVisit() {
        $sql = "SELECT c.*, 
                    MAX(v.data_analisi) AS ultima_visita
                FROM clienti c
                LEFT JOIN visite v ON c.cliente_id = v.cliente_id
                GROUP BY c.cliente_id
                ORDER BY c.cognome, c.nome";
        return $this->db->query($sql)->fetchAll();
    }

    /** Tutti i clienti (lista semplice) */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM clienti ORDER BY cognome, nome");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->query("SELECT * FROM clienti WHERE cliente_id = :id", ['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO clienti (nome, cognome, data_nascita, professione, telefono, email, indirizzo) 
                VALUES (:nome, :cognome, :data_nascita, :professione, :telefono, :email, :indirizzo)";
        $this->db->query($sql, [
            'nome'         => $data['nome'],
            'cognome'      => $data['cognome'],
            'data_nascita' => $data['data_nascita'] ?? null,
            'professione'  => $data['professione'] ?? null,
            'telefono'     => $data['telefono'] ?? null,
            'email'        => $data['email'] ?? null,
            'indirizzo'    => $data['indirizzo'] ?? null
        ]);
        return $this->db->getConnection()->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE clienti SET 
                nome = :nome, cognome = :cognome, data_nascita = :data_nascita,
                professione = :professione, telefono = :telefono,
                email = :email, indirizzo = :indirizzo
                WHERE cliente_id = :id";
        return $this->db->query($sql, [
            'id'           => $id,
            'nome'         => $data['nome'],
            'cognome'      => $data['cognome'],
            'data_nascita' => $data['data_nascita'] ?? null,
            'professione'  => $data['professione'] ?? null,
            'telefono'     => $data['telefono'] ?? null,
            'email'        => $data['email'] ?? null,
            'indirizzo'    => $data['indirizzo'] ?? null
        ]);
    }

    public function delete($id) {
        return $this->db->query("DELETE FROM clienti WHERE cliente_id = :id", ['id' => $id]);
    }
}
