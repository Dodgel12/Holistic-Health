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

class AppuntamentoController
{
    private $model;

    public function __construct()
    {
        Auth::require();
        $this->model = new Appuntamento();
    }

    public function index()
    {
        $appointments = $this->model->getAll();
        $clients = (new Client())->getAll();
        require_once __DIR__ . '/../views/appointments.php';
    }

    public function store()
    {
        $this->model->create($_POST);
        header('Location: appointments.php');
        exit;
    }

    public function delete($id)
    {
        $this->model->delete($id);
        header('Location: appointments.php');
        exit;
    }
}
