<?php
/**
 * Controller dei clienti.
 * Gestisce visualizzazione, creazione, modifica ed eliminazione clienti.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Models\Client;
use App\Models\SchedaAnalisi;

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
        $clients = $this->model->getAllWithLastVisit();
        require_once __DIR__ . '/../views/clients.php';
    }

    public function show($id)
    {
        $client = $this->model->getById($id);
        $schedaAnalisi = new SchedaAnalisi();
        $visite = $schedaAnalisi->getVisiteByClient($id);
        require_once __DIR__ . '/../views/client_view.php';
    }

    public function create()
    {
        $this->model->create($_POST);
        header('Location: clients.php');
        exit;
    }

    public function delete()
    {
        $id              = (int) ($_POST['cliente_id'] ?? 0);
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!$id) {
            header('Location: clients.php');
            exit;
        }

        // Verifica password dell'utente loggato
        $db = Database::getInstance();
        $user = $db->query(
            "SELECT * FROM users WHERE user_id = :uid",
            ['uid' => $_SESSION['user_id']]
        )->fetch();

        if (!$user || $user['password'] !== $confirmPassword) {
            // Password errata — torna con errore
            $_SESSION['delete_error'] = 'Password non corretta. Eliminazione annullata.';
            header('Location: clients.php');
            exit;
        }

        $this->model->delete($id);
        header('Location: clients.php');
        exit;
    }
}
