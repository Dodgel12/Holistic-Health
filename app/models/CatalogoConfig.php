<?php
/**
 * Modello cataloghi configurabili (alimenti, integratori, farmaci).
 */
namespace App\Models;

use App\Core\Database;

class CatalogoConfig
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAlimenti()
    {
        return $this->db->query("SELECT * FROM alimenti ORDER BY nome")->fetchAll();
    }

    public function getIntegratori()
    {
        return $this->db->query("SELECT * FROM integratori ORDER BY nome")->fetchAll();
    }

    public function getFarmaci()
    {
        return $this->db->query("SELECT * FROM farmaci ORDER BY nome")->fetchAll();
    }

    public function createItem($categoria, $nome, $descrizione = '')
    {
        $map = [
            'alimento' => ['table' => 'alimenti', 'id' => 'alimento_id'],
            'integratore' => ['table' => 'integratori', 'id' => 'integratore_id'],
            'farmaco' => ['table' => 'farmaci', 'id' => 'farmaco_id']
        ];

        if (!isset($map[$categoria])) {
            return false;
        }

        $table = $map[$categoria]['table'];
        $payloadNome = trim($nome);
        $payloadDescrizione = trim($descrizione);

        if ($this->db->hasColumn($table, 'descrizione')) {
            $sql = "INSERT INTO {$table} (nome, descrizione) VALUES (:nome, :descrizione)";
            $this->db->query($sql, [
                'nome' => $payloadNome,
                'descrizione' => $payloadDescrizione
            ]);
        } else {
            $sql = "INSERT INTO {$table} (nome) VALUES (:nome)";
            $this->db->query($sql, [
                'nome' => $payloadNome
            ]);
        }

        return $this->db->getConnection()->lastInsertId();
    }

    public function deleteItem($categoria, $id)
    {
        $map = [
            'alimento' => [
                'table' => 'alimenti',
                'id' => 'alimento_id',
                'links' => [
                    ['table' => 'visita_alimenti', 'field' => 'alimento_id'],
                    ['table' => 'piano_alimenti', 'field' => 'alimento_id']
                ]
            ],
            'integratore' => [
                'table' => 'integratori',
                'id' => 'integratore_id',
                'links' => [
                    ['table' => 'visita_integratori', 'field' => 'integratore_id'],
                    ['table' => 'piano_integratori', 'field' => 'integratore_id']
                ]
            ],
            'farmaco' => [
                'table' => 'farmaci',
                'id' => 'farmaco_id',
                'links' => [
                    ['table' => 'piano_farmaci', 'field' => 'farmaco_id']
                ]
            ]
        ];

        if (!isset($map[$categoria])) {
            return false;
        }

        $table = $map[$categoria]['table'];
        $idField = $map[$categoria]['id'];

        foreach ($map[$categoria]['links'] as $link) {
            if ($this->db->hasTable($link['table']) && $this->db->hasColumn($link['table'], $link['field'])) {
                $this->db->query(
                    "DELETE FROM {$link['table']} WHERE {$link['field']} = :id",
                    ['id' => $id]
                );
            }
        }

        $sql = "DELETE FROM {$table} WHERE {$idField} = :id";

        return $this->db->query($sql, ['id' => $id]);
    }
}
