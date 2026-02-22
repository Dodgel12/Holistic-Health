<?php
/**
 * Controller dei clienti.
 * Gestisce la logica applicativa per la creazione,
 * modifica e visualizzazione dei clienti.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Client;

class ClientController
{
    private $model;

    public function __construct()
    {
        Auth::require();
        $this->model = new Client();
    }

    public function index()
    {
        $clients = $this->model->getAll();
        require_once __DIR__ . '/../views/clients.php';
    }

    public function show($id)
    {
        $client = $this->model->getById($id);
        require_once __DIR__ . '/../views/client_view.php';
    }

    public function create()
    {
        $this->model->create($_POST);
        header('Location: clients.php');
        exit;
    }
}
