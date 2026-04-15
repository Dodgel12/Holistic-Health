<?php
/**
 * Router impostazioni applicative.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\SettingsController;

$controller = new SettingsController();
$action = $_GET['action'] ?? 'index';

switch ($action) {
	case 'question_create':
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$controller->createQuestion();
		}
		break;
	case 'question_update':
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$controller->updateQuestion();
		}
		break;
	case 'question_delete':
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$controller->deleteQuestion();
		}
		break;
	case 'client_delete':
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$controller->deleteClient();
		}
		break;
	case 'catalog_create':
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$controller->createCatalogItem();
		}
		break;
	case 'catalog_delete':
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$controller->deleteCatalogItem();
		}
		break;
	default:
		$controller->index();
		break;
}