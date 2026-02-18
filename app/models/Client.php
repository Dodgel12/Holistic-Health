<?php
/**
 * Modello Client.
 * Rappresenta un cliente e gestisce le operazioni
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
            'nome' => $data['nome'],
            'cognome' => $data['cognome'],
            'data_nascita' => $data['data_nascita'] ?? null,
            'professione' => $data['professione'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'email' => $data['email'] ?? null,
            'indirizzo' => $data['indirizzo'] ?? null
        ]);
        return $this->db->getConnection()->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE clienti SET 
                nome = :nome, 
                cognome = :cognome, 
                data_nascita = :data_nascita, 
                professione = :professione, 
                telefono = :telefono, 
                email = :email, 
                indirizzo = :indirizzo 
                WHERE cliente_id = :id";
        
        $params = [
            'id' => $id,
            'nome' => $data['nome'],
            'cognome' => $data['cognome'],
            'data_nascita' => $data['data_nascita'] ?? null,
            'professione' => $data['professione'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'email' => $data['email'] ?? null,
            'indirizzo' => $data['indirizzo'] ?? null
        ];
        
        return $this->db->query($sql, $params);
    }

    public function delete($id) {
        return $this->db->query("DELETE FROM clienti WHERE cliente_id = :id", ['id' => $id]);
    }
}
