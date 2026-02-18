<?php
/**
 * Controller dei questionari.
 * Gestisce la creazione, modifica e associazione
 * delle domande ai questionari.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Questionario;
use App\Models\Domanda;

class QuestionarioController {
    private $questionarioModel;

    public function __construct() {
        $this->questionarioModel = new Questionario();
    }

    public function index() {
        Auth::requireLogin();
        $questionari = $this->questionarioModel->getAll();
        // Implementazione base: restituisce i questionari in formato JSON
        echo json_encode($questionari);
    }
}
