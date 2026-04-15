<?php
/**
 * Modello per le domande configurabili dalle impostazioni.
 */
namespace App\Models;

use App\Core\Database;

class DomandaImpostazione
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM domande_impostazioni ORDER BY updated_at DESC, domanda_id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function create($testo)
    {
        $sql = "INSERT INTO domande_impostazioni (testo) VALUES (:testo)";
        $this->db->query($sql, ['testo' => $testo]);
        return $this->db->getConnection()->lastInsertId();
    }

    public function update($id, $testo)
    {
        $sql = "UPDATE domande_impostazioni SET testo = :testo WHERE domanda_id = :id";
        return $this->db->query($sql, ['id' => $id, 'testo' => $testo]);
    }

    public function delete($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return false;
        }

        $row = $this->db->query(
            "SELECT testo FROM domande_impostazioni WHERE domanda_id = :id LIMIT 1",
            ['id' => $id]
        )->fetch();

        if (!$row) {
            return false;
        }

            // Cancella anche le vecchie risposte legate al testo domanda.
            // Serve per non lasciare dati orfani nei DB gia' esistenti.
        if ($this->db->hasTable('risposte') && $this->db->hasColumn('risposte', 'domanda_testo')) {
            $this->db->query(
                "DELETE FROM risposte WHERE domanda_testo = :testo",
                ['testo' => $row['testo']]
            );
        }

        return $this->db->query("DELETE FROM domande_impostazioni WHERE domanda_id = :id", ['id' => $id]);
    }
}
