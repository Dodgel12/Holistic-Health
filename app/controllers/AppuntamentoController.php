<?php
/**
 * Controller degli appuntamenti.
 * Gestisce la logica per la pianificazione,
 * modifica e visualizzazione degli appuntamenti.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Appuntamento;
use App\Models\Client;

class AppuntamentoController {
    private $appuntamentoModel;

    public function __construct() {
        $this->appuntamentoModel = new Appuntamento();
    }

    public function index() {
        Auth::requireLogin();
        $appointments = $this->appuntamentoModel->getAll();
        $clients = (new Client())->getAll();
        
        $data = [
            'appointments' => $appointments,
            'clients' => $clients,
            'title' => 'Appuntamenti'
        ];
        extract($data);
        require_once __DIR__ . '/../views/appointments.php';
    }

    public function store() {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->appuntamentoModel->create($_POST);
            header('Location: appointments.php');
            exit;
        }
    }

    public function delete($id) {
        Auth::requireLogin();
        $this->appuntamentoModel->delete($id);
        header('Location: appointments.php');
        exit;
    }
}
