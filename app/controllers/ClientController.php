<?php
/**
 * Controller dei clienti.
 * Gestisce la logica applicativa per la creazione,
 * modifica e visualizzazione dei clienti.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Client;

class ClientController {
    private $clientModel;

    public function __construct() {
        $this->clientModel = new Client();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Verifica delle credenziali dell'amministratore (Naturopata).
            // Utilizziamo una query diretta alla tabella 'users' per verificare
            // l'identità dell'unico utente previsto dal sistema.
            
            $db = \App\Core\Database::getInstance();
            $stmt = $db->query("SELECT * FROM users WHERE username = :u", ['u' => $username]);
            $user = $stmt->fetch();

            if ($user && $password === $user['password']) {
                Auth::login($user);
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Credenziali non valide.";
                require_once __DIR__ . '/../views/login.php';
            }
        } else {
            if (Auth::check()) {
                header('Location: dashboard.php');
                exit;
            }
            require_once __DIR__ . '/../views/login.php';
        }
    }

    public function index() {
        Auth::requireLogin();
        $clients = $this->clientModel->getAll();
        $data = ['clients' => $clients, 'title' => 'Gestione Clienti'];
        extract($data);
        require_once __DIR__ . '/../views/clients.php';
    }

    public function show($id) {
        Auth::requireLogin();
        $client = $this->clientModel->getById($id);
        if (!$client) {
            header('Location: clients.php');
            exit;
        }
        $data = ['client' => $client, 'title' => 'Dettaglio Cliente'];
        extract($data);
        require_once __DIR__ . '/../views/client_view.php';
    }

    public function create() {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->clientModel->create($_POST);
            header('Location: clients.php');
            exit;
        }
    }
}
