<?php
/**
 * Controller appuntamenti.
 * Gestisce agenda, inserimento, stato e cancellazione.
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
        $clienteId = (int) ($_POST['cliente_id'] ?? 0);
        if ($clienteId <= 0) {
            header('Location: appointments.php?error=missing-client');
            exit;
        }

        $_POST['cliente_id'] = $clienteId;

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

    public function updateStatus()
    {
        $id = (int) ($_POST['appuntamento_id'] ?? 0);
        $stato = $_POST['stato'] ?? 'Programmato';

        if ($id > 0) {
            $this->model->updateStatus($id, $stato);
        }

        header('Location: appointments.php');
        exit;
    }
}
