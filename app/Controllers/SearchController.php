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
            echo json_encode(['companies' => [], 'contacts' => []]);
            exit;
        }

        $q = trim((string) ($_GET['q'] ?? ''));

        if (strlen($q) < 2) {
            header('Content-Type: application/json');
            echo json_encode(['companies' => [], 'contacts' => []]);
            exit;
        }

        $db = Database::connection();
        $like = '%' . $q . '%';

        $stmtC = $db->prepare("
            SELECT id, name, sector, city, status
            FROM companies
            WHERE name LIKE :q OR sector LIKE :q OR city LIKE :q OR email LIKE :q
            ORDER BY name ASC
            LIMIT 6
        ");
        $stmtC->execute(['q' => $like]);
        $companies = $stmtC->fetchAll(PDO::FETCH_ASSOC);

        $stmtK = $db->prepare("
            SELECT cc.id, cc.full_name, cc.job_title, cc.email, c.name AS company_name
            FROM company_contacts cc
            LEFT JOIN companies c ON c.id = cc.company_id
            WHERE cc.full_name LIKE :q OR cc.email LIKE :q OR cc.job_title LIKE :q OR c.name LIKE :q
            ORDER BY cc.full_name ASC
            LIMIT 6
        ");
        $stmtK->execute(['q' => $like]);
        $contacts = $stmtK->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode(['companies' => $companies, 'contacts' => $contacts]);
        exit;
    }
}
