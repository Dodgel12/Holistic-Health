<?php
/**
 * Modello del piano terapeutico del paziente.
 */
namespace App\Models;

use App\Core\Database;

class PianoTerapeutico
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getByClient($clienteId)
    {
        $sql = "SELECT * FROM piani_terapeutici
                WHERE cliente_id = :cliente_id
                ORDER BY data_inizio DESC, piano_id DESC";
        return $this->db->query($sql, ['cliente_id' => $clienteId])->fetchAll();
    }

    public function getById($pianoId)
    {
        $sql = "SELECT * FROM piani_terapeutici WHERE piano_id = :id";
        return $this->db->query($sql, ['id' => $pianoId])->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO piani_terapeutici
                (cliente_id, titolo, obiettivi, note, stato, data_inizio, data_fine)
                VALUES (:cliente_id, :titolo, :obiettivi, :note, :stato, :data_inizio, :data_fine)";

        $this->db->query($sql, [
            'cliente_id' => $data['cliente_id'],
            'titolo' => $data['titolo'],
            'obiettivi' => $data['obiettivi'] ?? '',
            'note' => $data['note'] ?? '',
            'stato' => $data['stato'] ?? 'Attivo',
            'data_inizio' => $data['data_inizio'],
            'data_fine' => !empty($data['data_fine']) ? $data['data_fine'] : null
        ]);

        return (int) $this->db->getConnection()->lastInsertId();
    }

    public function update($pianoId, $data)
    {
        $sql = "UPDATE piani_terapeutici
                SET titolo = :titolo,
                    obiettivi = :obiettivi,
                    note = :note,
                    stato = :stato,
                    data_inizio = :data_inizio,
                    data_fine = :data_fine
                WHERE piano_id = :piano_id";

        return $this->db->query($sql, [
            'piano_id' => $pianoId,
            'titolo' => $data['titolo'],
            'obiettivi' => $data['obiettivi'] ?? '',
            'note' => $data['note'] ?? '',
            'stato' => $data['stato'] ?? 'Attivo',
            'data_inizio' => $data['data_inizio'],
            'data_fine' => !empty($data['data_fine']) ? $data['data_fine'] : null
        ]);
    }

    public function delete($pianoId)
    {
        return $this->db->query("DELETE FROM piani_terapeutici WHERE piano_id = :id", ['id' => $pianoId]);
    }

    public function syncLinks($pianoId, $alimenti = [], $integratori = [], $farmaci = [])
    {
        $this->db->query("DELETE FROM piano_alimenti WHERE piano_id = :id", ['id' => $pianoId]);
        $this->db->query("DELETE FROM piano_integratori WHERE piano_id = :id", ['id' => $pianoId]);
        $this->db->query("DELETE FROM piano_farmaci WHERE piano_id = :id", ['id' => $pianoId]);

        foreach ($alimenti as $alimentoId) {
            $this->db->query(
                "INSERT INTO piano_alimenti (piano_id, alimento_id) VALUES (:piano_id, :alimento_id)",
                ['piano_id' => $pianoId, 'alimento_id' => (int) $alimentoId]
            );
        }

        foreach ($integratori as $integratoreId) {
            $this->db->query(
                "INSERT INTO piano_integratori (piano_id, integratore_id) VALUES (:piano_id, :integratore_id)",
                ['piano_id' => $pianoId, 'integratore_id' => (int) $integratoreId]
            );
        }

        foreach ($farmaci as $farmacoId) {
            $this->db->query(
                "INSERT INTO piano_farmaci (piano_id, farmaco_id) VALUES (:piano_id, :farmaco_id)",
                ['piano_id' => $pianoId, 'farmaco_id' => (int) $farmacoId]
            );
        }
    }

    public function getSelectedCatalogIds($pianoId)
    {
        $alimenti = $this->db->query(
            "SELECT alimento_id FROM piano_alimenti WHERE piano_id = :id",
            ['id' => $pianoId]
        )->fetchAll(\PDO::FETCH_COLUMN);

        $integratori = $this->db->query(
            "SELECT integratore_id FROM piano_integratori WHERE piano_id = :id",
            ['id' => $pianoId]
        )->fetchAll(\PDO::FETCH_COLUMN);

        $farmaci = $this->db->query(
            "SELECT farmaco_id FROM piano_farmaci WHERE piano_id = :id",
            ['id' => $pianoId]
        )->fetchAll(\PDO::FETCH_COLUMN);

        return [
            'alimenti' => array_map('intval', $alimenti),
            'integratori' => array_map('intval', $integratori),
            'farmaci' => array_map('intval', $farmaci)
        ];
    }
}
