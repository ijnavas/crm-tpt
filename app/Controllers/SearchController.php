<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use PDO;

final class SearchController extends Controller
{
    public function search(): void
    {
        if (!Auth::check()) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit;
        }

        $q = trim((string) ($_GET['q'] ?? ''));

        if (strlen($q) < 2) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit;
        }

        $db   = Database::connection();
        $like = '%' . $q . '%';

        // Empresas
        $stmt = $db->prepare("SELECT id, name, sector, city, status FROM companies WHERE name LIKE :q OR sector LIKE :q OR city LIKE :q ORDER BY name ASC LIMIT 5");
        $stmt->execute(['q' => $like]);
        $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Contactos
        $stmt = $db->prepare("SELECT cc.id, cc.full_name, cc.job_title, cc.email, c.name AS company_name FROM company_contacts cc LEFT JOIN companies c ON c.id = cc.company_id WHERE cc.full_name LIKE :q OR cc.email LIKE :q OR c.name LIKE :q ORDER BY cc.full_name ASC LIMIT 5");
        $stmt->execute(['q' => $like]);
        $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Leads
        $stmt = $db->prepare("SELECT id, full_name, company_name, status FROM leads WHERE full_name LIKE :q OR company_name LIKE :q OR email LIKE :q ORDER BY created_at DESC LIMIT 5");
        $stmt->execute(['q' => $like]);
        $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Tareas
        $stmt = $db->prepare("SELECT id, title, type, status, due_date FROM tasks WHERE title LIKE :q AND status <> 'completada' ORDER BY due_date ASC LIMIT 4");
        $stmt->execute(['q' => $like]);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Contratos
        $stmt = $db->prepare("SELECT c.id, c.title, c.service_type, c.status, co.name AS company_name FROM contracts c JOIN companies co ON co.id = c.company_id WHERE c.title LIKE :q OR co.name LIKE :q ORDER BY c.created_at DESC LIMIT 4");
        $stmt->execute(['q' => $like]);
        $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Trabajadores
        $stmt = $db->prepare("SELECT id, full_name, dni, disability_type, status FROM workers WHERE full_name LIKE :q OR dni LIKE :q OR skills LIKE :q ORDER BY full_name ASC LIMIT 4");
        $stmt->execute(['q' => $like]);
        $workers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode(compact('companies', 'contacts', 'leads', 'tasks', 'contracts', 'workers'));
        exit;
    }

    public function entities(): void
    {
        if (!Auth::check()) { echo json_encode([]); exit; }

        $type = trim((string) ($_GET['type'] ?? 'company'));
        $db   = Database::connection();

        $rows = match($type) {
            'company'  => $db->query("SELECT id, name FROM companies ORDER BY name ASC LIMIT 100")->fetchAll(PDO::FETCH_ASSOC),
            'lead'     => $db->query("SELECT id, COALESCE(company_name, full_name) AS name FROM leads ORDER BY name ASC LIMIT 100")->fetchAll(PDO::FETCH_ASSOC),
            'contact'  => $db->query("SELECT id, full_name AS name FROM company_contacts ORDER BY full_name ASC LIMIT 100")->fetchAll(PDO::FETCH_ASSOC),
            'contract' => $db->query("SELECT id, title AS name FROM contracts ORDER BY title ASC LIMIT 100")->fetchAll(PDO::FETCH_ASSOC),
            default    => [],
        };

        header('Content-Type: application/json');
        echo json_encode($rows);
        exit;
    }
}