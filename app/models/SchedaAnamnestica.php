<?php
/**
 * Modello SchedaAnamnestica.
 * Crea e recupera i dati anamnestici completi del cliente.
 */
namespace App\Models;

use App\Core\Database;

class SchedaAnamnestica {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getByVisitaId($visitaId) {
        $scheda = $this->db->query(
            "SELECT * FROM scheda_anamnestica WHERE visita_id = :visita_id",
            ['visita_id' => $visitaId]
        )->fetch();

        if ($scheda) {
            $id = $scheda['anamnesi_id'];
            $scheda['stile_vita']  = $this->db->query("SELECT * FROM stile_vita WHERE anamnesi_id = :id", ['id' => $id])->fetch();
            $scheda['personale']   = $this->db->query("SELECT * FROM anamnesi_personali WHERE anamnesi_id = :id", ['id' => $id])->fetch();
            $scheda['psico_fisico']= $this->db->query("SELECT * FROM stato_psico_fisico WHERE anamnesi_id = :id", ['id' => $id])->fetch();
            $scheda['sonno']       = $this->db->query("SELECT * FROM qualita_sonno WHERE anamnesi_id = :id", ['id' => $id])->fetch();
        }

        return $scheda;
    }

    /**
     * Crea la scheda anamnestica completa e tutte le tabelle collegate.
     */
    public function create($visitaId, $data) {
        // 1. Scheda anamnestica principale
        $this->db->query(
            "INSERT INTO scheda_anamnestica (visita_id, osservazioni_finali) VALUES (:visita_id, :osservazioni)",
            [
                'visita_id'     => $visitaId,
                'osservazioni'  => $data['osservazioni_finali'] ?? ''
            ]
        );
        $anamnesiId = $this->db->getConnection()->lastInsertId();

        // 2. Stile di vita
        $this->db->query(
            "INSERT INTO stile_vita (anamnesi_id, alimentazione, attivita_fisica_tipo, attivita_fisica_frequenza, descrizione)
             VALUES (:anamnesi_id, :alimentazione, :tipo, :frequenza, :descrizione)",
            [
                'anamnesi_id'  => $anamnesiId,
                'alimentazione'=> $data['alimentazione'] ?? '',
                'tipo'         => $data['attivita_fisica_tipo'] ?? '',
                'frequenza'    => $data['attivita_fisica_frequenza'] ?? '',
                'descrizione'  => $data['stile_vita_descrizione'] ?? ''
            ]
        );

        // 3. Anamnesi personali
        $this->db->query(
            "INSERT INTO anamnesi_personali 
                (anamnesi_id, allergie, allergie_dettagli, interventi_chirurgici,
                 patologie, patologie_dettagli, alcol, fumo, farmaci_correnti)
             VALUES (:anamnesi_id, :allergie, :allergie_det, :interventi,
                     :patologie, :patologie_det, :alcol, :fumo, :farmaci)",
            [
                'anamnesi_id'    => $anamnesiId,
                'allergie'       => (int) ($data['allergie'] ?? 0),
                'allergie_det'   => $data['allergie_dettagli'] ?? '',
                'interventi'     => $data['interventi_chirurgici'] ?? '',
                'patologie'      => (int) ($data['patologie'] ?? 0),
                'patologie_det'  => $data['patologie_dettagli'] ?? '',
                'alcol'          => (int) ($data['alcol'] ?? 0),
                'fumo'           => (int) ($data['fumo'] ?? 0),
                'farmaci'        => $data['farmaci_correnti'] ?? ''
            ]
        );

        // 4. Stato psico-fisico
        $this->db->query(
            "INSERT INTO stato_psico_fisico 
                (anamnesi_id, livello_stress, concentrazione, umore, ansia, motivazione)
             VALUES (:anamnesi_id, :stress, :concentrazione, :umore, :ansia, :motivazione)",
            [
                'anamnesi_id'   => $anamnesiId,
                'stress'        => (int) ($data['livello_stress'] ?? 5),
                'concentrazione'=> (int) ($data['concentrazione'] ?? 5),
                'umore'         => $data['umore'] ?? '',
                'ansia'         => (int) ($data['ansia'] ?? 0),
                'motivazione'   => $data['motivazione'] ?? ''
            ]
        );

        // 5. Qualità sonno
        $this->db->query(
            "INSERT INTO qualita_sonno 
                (anamnesi_id, ore_sonno, risvegli_notturni, qualita_percepita, difficolta_addormentarsi)
             VALUES (:anamnesi_id, :ore, :risvegli, :qualita, :difficolta)",
            [
                'anamnesi_id' => $anamnesiId,
                'ore'         => !empty($data['ore_sonno']) ? $data['ore_sonno'] : null,
                'risvegli'    => !empty($data['risvegli_notturni']) ? (int) $data['risvegli_notturni'] : 0,
                'qualita'     => $data['qualita_percepita'] ?? '',
                'difficolta'  => (int) ($data['difficolta_addormentarsi'] ?? 0)
            ]
        );

        return $anamnesiId;
    }
}